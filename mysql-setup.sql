-- Table structure for URL Shortener

CREATE TABLE `shortenedurls` (
	`id` char(6) NOT NULL,
	`url` varchar(255) NOT NULL,
	`created` int(10) unsigned NOT NULL,
	`creator` char(15) NOT NULL,
	PRIMARY KEY  (`id`),
	UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
