CREATE TABLE IF NOT EXISTS `civicrm_post_nummer` (
  `post_code` varchar(15) NOT NULL,
  `post_city` varchar(128) DEFAULT NULL,
  `community_number` varchar(15) DEFAULT NULL,
  `community_name` varchar(128) DEFAULT NULL,
  `category` char(1) DEFAULT NULL,
  PRIMARY KEY (`post_code`),
  UNIQUE KEY `post_code_UNIQUE` (`post_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
