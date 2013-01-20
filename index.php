<?php
	namespace UrlShortener;

	require __DIR__.'/config.php';
	require __DIR__.'/program/session.php';
	require __DIR__.'/program/urls.php';

	mt_srand(time());

	if (isset($_GET['redirect']))
	{
		// We're redirecting!
		$url = get_original_url($_GET['redirect']);
		if (!$url) die('Unknown URL');
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$url);
		exit;
	}

	if (USE_HTTPS) assert_https();

	//Otherwise it's the web UI

	$have_session = have_session();
	$short_url = '';

	function post_req_login()
	{
		global $have_session;
		$have_session = login($_POST['password']);
	}

	function post_req_shorten()
	{
		global $short_url;
		$short_url = shorten_url($_POST['url']);
		if ($short_url === false) $short_url = '-error-';
	}

	function post_req_logout()
	{
		global $have_session;
		set_session($have_session = false);
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		call_user_func('UrlShortener\post_req_'.$_POST['action']);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
	<meta name="language" content="en" />
	<meta name="author" content="Vojtěch Král" />
	<link href="page/css.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript">
		window.onload = function()
		{
			document.getElementsByClassName("focus")[0].focus();
		}
	</script>
	<title><?php echo TITLE ?></title>
</head>
<body>
	<?php if(!$have_session) { ?>
		<div id="login" class="center round white">
			<form action="" method="post">
				<input type="hidden" name="action" value="login" />
				<label for="password">Password:</label>
				<input type="password" name="password" id="password" class="in-text focus round-small"/>
			</form>
		</div>
	<?php } else { ?>
		<div id="app" class="center round white">
			<form action="" method="post">
				<input type="submit" name="action" value="logout" id="logout" class="round-small" />
			</form>
			<form action="" method="post">
				<input type="hidden" name="action" value="shorten" />
				<label for="url">Shorten URL:</label>
				<input type="text" name="url" value="<?php echo htmlspecialchars($short_url) ?>" id="url" class="in-text focus round-small" />
			</form>
		</div>
	<?php } ?>
	<div id=footer>
		Personal URL Shortener ©2013 Vojtech Kral. Fork me on <a href="https://github.com/kralyk/personal-url-shortener">GitHub</a>!
	</div>
</body>
</html>
