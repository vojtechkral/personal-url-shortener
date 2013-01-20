<?php
namespace UrlShortener;

define('SES_NAME', 'UrlShortenerSesID');
define('SES_FIELD', 'haveSession');

function assert_https()
{
	if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == '')
	{
		$location = 'https://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '')
		            .$_SERVER['REQUEST_URI'];
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.$location);
		exit;
	}
}

function have_session()
{
	\session_name(SES_NAME);
	\session_set_cookie_params(LOGIN_TTL);
	if (!\session_start()) die('Could not start session');
	return !!$_SESSION[SES_FIELD];
}

function set_session($bool)
{
	$_SESSION[SES_FIELD] = !!$bool;
}

function login($pw)
{
	$r = \hash('sha256', PW_SALT.$pw) == PW_HASH;
	set_session($r);
	return $r;
}
