<?php
namespace UrlShortener;


function db_error($mysqli)
{
	die('DB Error: '.$mysqli->connect_errno.' - '.$mysqli->connect_error);
}

function db_connect()
{
	static $mysqli = false;
	if (!$mysqli)
	{
		$mysqli = new \mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DBNAME);
		if ($mysqli->connect_error) db_error($mysqli);
	}
	return $mysqli;
}

function id_assigned($id)
{
	$db = db_connect();
	if (!$res = $db->query('SELECT id FROM '.DB_TABLE.' WHERE id="'.$id.'"')) db_error($db);
	return $res->num_rows > 0;
}

function base_conv($i, $base, $digits)
{
	$res = '';
	$chars = URL_CHARS;
	for (; ($i > 0) && ($digits > 0); $digits--)
	{
		$rem = $i % $base;
		$i = (int)($i / $base);  //Because the goddamned PHP doesn't even have integer division.
		$res = $chars[$rem].$res;
	}
	for (; $digits > 0; $digits--) $res = $chars[0].$res;
	return $res;
}

function new_short_url()
{
	$nchars = \strlen(URL_CHARS);

	//Following lines contain black magic
	//If PHP provided reasonable integers (bigger, unsigned,...)
	//I wouldn't have had to become a Death eater...

	$chars = URL_CHARS;
	$count_digits = \floor(\log(PHP_INT_MAX, $nchars));
	if ($count_digits > URL_SIZE) $count_digits = URL_SIZE;
	$count_max = \pow($nchars, $count_digits)-1;  //Should be integer
	$rd_digits = URL_SIZE > $count_digits ? URL_SIZE-$count_digits : 0;
	$rd_max = \pow($nchars, $rd_digits);
	if ($rd_max > PHP_INT_MAX) $rd_max = PHP_INT_MAX;

	for ($t = 0; $t < 3; $t++)
	{
		$rd = '';
		for ($i = 0; $i < $rd_digits; $i++) $rd .= $chars[mt_rand(0, $nchars-1)];
		$x = mt_rand(0, $count_max);

		for ($i = $x; $i <= $count_max; $i++)
		{
			$res = $rd.base_conv($i, $nchars, $count_digits);
			if (!id_assigned($res)) return $res;
		}
		for ($i = $x-1; $i >= 0; $i--)
		{
			$res = $rd.base_conv($i, $nchars, $count_digits);
			if (!id_assigned($res)) return $res;
		}
	}

	//Uhh, no available short URL found yet?
	//This will have to be done sequentially.
	//We're probably running out of time, but let's try...
	for ($t = 0; $t < $rd_max; $t++)   //Works great for rd_digits = 0 too, because then rd_max = 1
	{
		$rd = base_conv($t, $nchars, $rd_digits);
		for ($i = 0; $i <= $count_max; $i++)
		{
			$res = $rd.base_conv($i, $nchars, $count_digits);
			if (!id_assigned($res)) return $res;
		}
	}

	//Still nothing? This is just wrong...
	return false;
}

function is_self_url($url)
{
	$prefix = \substr(URL_PREFIX, strpos(URL_PREFIX, '://')+3);
	$url = \substr($url, \strpos($url, '://')+3);
	$path = \substr($url, \strlen($prefix));
	$url = \substr($url, 0, \strlen($prefix));

	if (($url == $prefix) &&
	    \preg_match('/^['.URL_CHARS.']{'.URL_SIZE.'}$/', $path)) return $path;
	else return false;
}

function shorten_url($url)
{
	$url = \trim($url);
	if (!\filter_var($url, FILTER_VALIDATE_URL)) return false;
	if ($code = is_self_url($url)) return $code;

	$db = db_connect();
	$url = $db->real_escape_string($url);

	//Check wether this url is already shortened
	if (!$res = $db->query('SELECT id FROM '.DB_TABLE.' WHERE url="'.$url.'"')) db_error($db);
	if ($res->num_rows > 0)
	{
		//Already shortened
		$row = $res->fetch_assoc();
		return $row['id'];
	} else
	{
		//Create new one
		$short = new_short_url();
		if (!$short) return false;

		if (!$db->query('LOCK TABLES '.DB_TABLE.' WRITE;')) db_error($db);
		$res = $db->query('INSERT INTO '.DB_TABLE.' (id, url, created, creator) VALUES ("'.$db->real_escape_string($short).
		                  '","'.$url.'","'.time().'","'.$db->real_escape_string($_SERVER['REMOTE_ADDR']).'")');
		$db->query('UNLOCK TABLES');
		if (!$res) db_error($db);

		return $short;
	}
}

function get_original_url($id)
{
	$db = db_connect();
	if (!$res = $db->query('SELECT url FROM '.DB_TABLE.' WHERE id="'.$id.'"')) db_error($db);
	if ($res->num_rows == 0) return false;
	else
	{
		$row = $res->fetch_assoc();
		return $row['url'];   //Some servers don't like the shorthand syntax, don't know why...
	}
}
