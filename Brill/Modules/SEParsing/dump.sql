
CREATE TABLE IF NOT EXISTS `sep_keywords` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `region_id` smallint(5) unsigned NOT NULL,
  `yandex` enum('NoData','Busy','Сalculated','Error') NOT NULL,
  `google` enum('NoData','Busy','Сalculated','Error') NOT NULL,
  `rambler` enum('NoData','Busy','Сalculated','Error') NOT NULL,
  `set_id` mediumint(8) unsigned NOT NULL,
  `thematic_id` smallint(6) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `region_id` (`region_id`),
  KEY `status` (`yandex`),
  KEY `set_id` (`set_id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `sep_positions` (
  `keyword_id` mediumint(8) unsigned NOT NULL,
  `url_id` int(10) unsigned NOT NULL,
  `site_id` mediumint(8) unsigned NOT NULL,
  `pos` tinyint(3) unsigned NOT NULL,
  `pos_dot` tinyint(3) unsigned NOT NULL,
  `links_search` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  KEY `pos` (`pos`,`pos_dot`),
  KEY `fk_site` (`site_id`),
  KEY `fk_url` (`url_id`),
  KEY `fk_keyword` (`keyword_id`),
  KEY `date` (`date`)
) ENGINE=innodb DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `sep_sets` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `sep_sites` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `domain_create` timestamp NOT NULL default '0000-00-00 00:00:00',
  `domain_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `site` USING BTREE (`name`,`domain_create`),
  KEY `domain_id` (`domain_id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `sep_thematics` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(128) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `sep_urls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `url` text NOT NULL,
  `site_id` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8;