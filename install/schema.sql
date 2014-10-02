CREATE TABLE IF NOT EXISTS `__TABLE_PREFIX__dscategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ds_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `header` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `position` int(10) NOT NULL DEFAULT '500',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`,`ds_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `__TABLE_PREFIX__dscategory_documents` (
  `category_id` int(11) NOT NULL default '0',
  `document_id` int(11) NOT NULL default '0',
  `field_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`category_id`,`document_id`,`field_id`),
  KEY `document_id` (`document_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;