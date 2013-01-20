<?php
namespace UrlShortener;

// Page title
define('TITLE', '');

// Password
define('PW_SALT', 'place random salt here');
define('PW_HASH', 'append your password to the salt, hash it all with sha-256 and copy here');

// Login lifetime (in seconds)
// Default is 90 days
define('LOGIN_TTL', '7776000');

// DB information
define('DB_HOST', 'db server');
define('DB_USER', 'db username');
define('DB_PASSWORD', 'that users password');
define('DB_DBNAME', 'db name');
define('DB_TABLE', 'shortenedurls');

// Shortened URL prefix
define('URL_PREFIX', 'http://'.$_SERVER['HTTP_HOST'].'/');

// Shortened URL size (just the suffix)
// 1~6 chars. Using more than that is not recommended, could cause trouble on some 32-bit machines.
// Don't forget to modify .htaccess accordingly (regexp)
define('URL_SIZE', 5);

// Shortened URL character set
// By default all the unreserved url chars
// Don't forget to modify .htaccess accordingly (regexp)
define('URL_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_.~-');


/*
 * How many URLs you can assign with default character set?
 *
 * URL suffix size        No. of URLS
 * 1                      66
 * 2                      4356         (~4   k)
 * 3                      287496       (~290 k)
 * 4                      18974736     (~19  M)
 * 5                      1252332576   (~1   G)
 * 6                      82653950016  (~80  G)
 */
