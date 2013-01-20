<?php
namespace UrlShortener;

// Page title
define('TITLE', '');

// Secure connection
define('USE_HTTPS', true);

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
// By default 0-9, a-z, A-Z
// (Note: facebook ignores trailing '_' and '-' in URL)
// Don't forget to modify .htaccess accordingly (regexp)
define('URL_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');


/*
 * How many URLs you can assign with default character set?
 *
 * URL suffix size        No. of URLS
 * 1                      62
 * 2                      3844         (~4   k)
 * 3                      238328       (~240 k)
 * 4                      14776336     (~15  M)
 * 5                      916132832    (~1   G)
 * 6                      56800235584  (~57  G)
 */
