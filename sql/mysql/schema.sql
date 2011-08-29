CREATE TABLE IF NOT EXISTS `user_relationship_types` (
  `rtid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `plural_name` varchar(255) NOT NULL DEFAULT '',
  `is_oneway` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_reciprocal` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `requires_approval` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `expires_val` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rtid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_relationship_elaborations` (
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `elaboration` longtext NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_relationships` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `requester_id` int(10) unsigned NOT NULL DEFAULT '0',
  `requestee_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rtid` int(10) unsigned NOT NULL DEFAULT '0',
  `approved` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0',
  `flags` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`requester_id`,`requestee_id`,`rtid`),
  KEY `requester_id` (`requester_id`),
  KEY `requestee_id` (`requestee_id`),
  KEY `rtid` (`rtid`),
  KEY `rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userexp` (
  `contentobject_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`contentobject_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `userexp_data` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `contentobject_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userexp_data_content_id` ( `contentobject_id`, `user_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `qavote_data` (
  `id` int(11) NOT NULL auto_increment,
  `created_at` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_key` varchar(32) NOT NULL,
  `vote_value` int(11) NOT NULL,
  `contentobject_id` int(11) NOT NULL,
  `contentobject_attribute_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_session_key` ( `user_id`,`session_key` ),
  KEY `contentobject_id_contentobject_attribute_id` ( `contentobject_id`, `contentobject_attribute_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `qavote` (
  `contentobject_id` int(11) NOT NULL,
  `contentobject_attribute_id` int(11) NOT NULL,
  `vote_count` int(11) NOT NULL,
  `vote_up` int(11) NOT NULL,
  `vote_down` int(11) NOT NULL,
  PRIMARY KEY (`contentobject_id`, `contentobject_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gamestats` (
  `id` int(11) NOT NULL auto_increment,
  `gameid` int(11) NOT NULL,  
  `category` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL,  
  `score` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `masterserver` (
  `useNat` tinyint(1) NOT NULL,
  `gameType` varchar(256) NOT NULL,
  `gameName` varchar(256) NOT NULL,
  `connectedPlayers` mediumint(16) NOT NULL,
  `playerLimit` smallint(8) NOT NULL,
  `internalIp` varchar(64) NOT NULL,
  `internalPort` smallint(8) unsigned NOT NULL,
  `externalIP` varchar(64) NOT NULL,
  `externalPort` smallint(8) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `passwordProtected` tinyint(1) NOT NULL,
  `comment` blob NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gameName_3` (`gameName`),
  FULLTEXT KEY `gameName_2` (`gameName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `user_stream` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
