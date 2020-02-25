CREATE TABLE IF NOT EXISTS `block` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `extra` json DEFAULT NULL,
  `i_type` tinyint(4) NOT NULL DEFAULT '0',
  `weight` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `setting` json DEFAULT NULL,
  `weight` float NOT NULL DEFAULT '0',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `cate-sort` (`category`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `val` varchar(255) DEFAULT NULL,
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='config series';

CREATE TABLE IF NOT EXISTS `cooperate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `config` json DEFAULT NULL,
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `dict_origin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'pre id',
  `ids` varchar(255) NOT NULL DEFAULT '' COMMENT 'id series',
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `depth` tinyint(4) NOT NULL DEFAULT '0',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `income` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `i_item` tinyint(4) NOT NULL,
  `i_status` tinyint(4) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL DEFAULT '0',
  `args` json DEFAULT NULL,
  `sign` int(10) unsigned NOT NULL DEFAULT '0',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid-tms` (`uid`,`tms_create`),
  KEY `uid-item-sign` (`uid`,`i_item`,`sign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `tuid` int(10) unsigned NOT NULL COMMENT 'teacher uid',
  `title` varchar(255) NOT NULL COMMENT 'lesson title',
  `brief` text COMMENT 'brief lesson introduce',
  `category` varchar(255) NOT NULL DEFAULT '',
  `tags` json DEFAULT NULL,
  `i_form` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'teaching form',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT 'sign up price',
  `quota` int(11) NOT NULL DEFAULT '0' COMMENT 'quota limit, -1 for infi',
  `homework` json DEFAULT NULL,
  `plan` json DEFAULT NULL COMMENT 'lesson plan',
  `i_step` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'lesson step',
  `extra` json DEFAULT NULL,
  `tms_step` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `t-s` (`tuid`,`i_step`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_access` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `i_event` tinyint(4) NOT NULL COMMENT 'event id',
  `args` json DEFAULT NULL COMMENT 'event args',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `l-u-e` (`lesson_id`,`uid`,`i_event`),
  KEY `u-e` (`uid`,`i_event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `i_type` tinyint(4) NOT NULL DEFAULT '0',
  `refer` varchar(255) NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `args` json DEFAULT NULL,
  `i_status` tinyint(4) NOT NULL DEFAULT '0',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_board` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'refer id',
  `id_` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'primary id',
  `uid` int(10) unsigned NOT NULL,
  `lesson_id` int(10) unsigned NOT NULL,
  `i_type` tinyint(4) NOT NULL DEFAULT '0',
  `message` json DEFAULT NULL,
  `stats` json DEFAULT NULL,
  `weight` int(11) NOT NULL DEFAULT '0' COMMENT 'rank weight',
  `extra` json DEFAULT NULL,
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `l-w` (`lesson_id`,`weight`),
  KEY `l-t` (`lesson_id`,`tms_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_hub` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tsn` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `weight` float NOT NULL DEFAULT '0',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tsn` (`tsn`),
  FULLTEXT KEY `tag` (`tag`),
  FULLTEXT KEY `title` (`tag`) /*!50100 WITH PARSER `ngram` */ 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_prepare` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int(10) unsigned NOT NULL,
  `i_type` tinyint(4) NOT NULL,
  `content` json DEFAULT NULL,
  `seqno` float NOT NULL DEFAULT '0',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `l-s` (`lesson_id`,`seqno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_process` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `i_event` tinyint(4) NOT NULL COMMENT 'event id',
  `args` json DEFAULT NULL COMMENT 'event args',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `u-e` (`uid`,`i_event`),
  KEY `l` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_promote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `i_type` tinyint(4) NOT NULL DEFAULT '0',
  `i_status` tinyint(4) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL,
  `series_id` int(10) unsigned NOT NULL,
  `lesson_id` int(10) unsigned NOT NULL,
  `origin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `discount` int(11) NOT NULL DEFAULT '0',
  `commission` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `extra` json DEFAULT NULL,
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `l-u` (`lesson_id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int(10) unsigned NOT NULL,
  `from_uid` int(10) unsigned NOT NULL COMMENT 'sender',
  `i_type` tinyint(4) NOT NULL COMMENT 'record type',
  `content` json DEFAULT NULL COMMENT 'lesson content',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `l-i` (`lesson_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_series` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `uid` int(10) unsigned NOT NULL COMMENT 'producer uid',
  `name` varchar(255) NOT NULL COMMENT 'series name',
  `introduce` json DEFAULT NULL COMMENT 'series introduce',
  `scheme` json DEFAULT NULL COMMENT 'series scheme',
  `lesson_ids` json DEFAULT NULL COMMENT 'contained lessons',
  `i_status` tinyint(4) DEFAULT '0',
  `extra` json DEFAULT NULL,
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_square` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tags` json DEFAULT NULL,
  `row` int(11) DEFAULT NULL,
  `col` tinyint(4) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `i_item` tinyint(4) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '+: income, -:outcome',
  `balance` int(11) NOT NULL DEFAULT '0',
  `args` json DEFAULT NULL COMMENT 'item args',
  `sign` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tms` (`tms`),
  KEY `uid-tms` (`uid`,`tms`),
  KEY `uid-item-sign` (`uid`,`i_item`,`sign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `i_type` tinyint(4) NOT NULL COMMENT 'type id',
  `args` json DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `u-i` (`uid`,`i_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `i_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'order type id',
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `lesson_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'lesson id',
  `origin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'origin id',
  `order_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `paid_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `payoff_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `i_pay_way` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'pay method',
  `pay_sn` varchar(255) NOT NULL DEFAULT '' COMMENT 'pay serial number',
  `i_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'order status',
  `extra` json DEFAULT NULL,
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `u-t` (`uid`,`tms_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `payoff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `order_status` tinyint(4) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL,
  `i_item` tinyint(4) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uoi` (`uid`,`order_id`,`i_item`),
  KEY `order` (`order_id`),
  KEY `user-item` (`uid`,`i_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rating` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_id` int(10) unsigned NOT NULL COMMENT 'lesson id',
  `tuid` int(10) unsigned NOT NULL COMMENT 'teacher uid',
  `suid` int(10) unsigned NOT NULL COMMENT 'student uid',
  `i_part` tinyint(4) NOT NULL COMMENT 'rating part id',
  `score` tinyint(4) NOT NULL DEFAULT '0',
  `i_status` tinyint(4) NOT NULL DEFAULT '0',
  `remark` text COMMENT 'student comment',
  `reply` text COMMENT 'teacher reply',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_reply` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lesson` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `order_id` int(10) unsigned NOT NULL,
  `union_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lesson_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'lesson id',
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `amount` int(10) unsigned NOT NULL COMMENT 'refund amount',
  `i_status` tinyint(4) NOT NULL COMMENT 'refund status',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tms_finish` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT 'active uid',
  `_uid` int(10) unsigned NOT NULL COMMENT 'passive uid',
  `i_type` tinyint(4) NOT NULL COMMENT 'relation type',
  `score` int(10) unsigned NOT NULL COMMENT 'relation score',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `_uid` (`_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `scope_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `depth` tinyint(4) NOT NULL DEFAULT '0',
  `rank` tinyint(4) NOT NULL DEFAULT '0',
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `scope_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scope` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'group name',
  `auths` json DEFAULT NULL COMMENT 'group auths',
  PRIMARY KEY (`id`),
  UNIQUE KEY `s-g` (`scope`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `scope_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `groups` json DEFAULT NULL COMMENT 'auth group',
  `auths` json DEFAULT NULL COMMENT 'user auths',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `i_type` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `datum` json DEFAULT NULL,
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type-item` (`i_type`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `stats_period` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `i_period` tinyint(4) NOT NULL COMMENT 'period type',
  `dom` varchar(255) NOT NULL COMMENT 'domain',
  `idx` int(11) NOT NULL COMMENT 'index',
  `val` int(11) NOT NULL COMMENT 'value',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `dom-tms` (`dom`,`tms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `stats_timely` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dom` varchar(255) NOT NULL COMMENT 'domain',
  `idx` int(11) NOT NULL COMMENT 'index',
  `val` int(11) NOT NULL COMMENT 'value',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dom-idx` (`dom`,`idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tuid` int(10) unsigned NOT NULL COMMENT 'teacher uid',
  `datum` json DEFAULT NULL COMMENT 'information about',
  `i_status` tinyint(4) NOT NULL DEFAULT '0',
  `rate_count` int(11) NOT NULL DEFAULT '0',
  `rate_score` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'avg score',
  `rate_parts` json DEFAULT NULL COMMENT 'rating by part',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tuid` (`tuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ticket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'refer to',
  `i_type` tinyint(4) NOT NULL COMMENT 'review type',
  `uid` int(10) unsigned NOT NULL COMMENT 'author uid',
  `_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'assign uid',
  `content` json DEFAULT NULL,
  `i_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'review status',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `_id` (`_id`),
  KEY `uid` (`uid`),
  KEY `type-status-id` (`i_type`,`i_status`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `union_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `i_type` tinyint(4) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL,
  `origin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_set` json DEFAULT NULL COMMENT 'set of union order',
  `order_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `paid_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `i_pay_way` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'pay method',
  `pay_sn` varchar(255) NOT NULL DEFAULT '' COMMENT 'pay serial number',
  `i_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'order status',
  `extra` json DEFAULT NULL,
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `u-t` (`uid`,`tms_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `i_role` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'role type',
  `sn` varchar(255) NOT NULL COMMENT 'serial number',
  `name` varchar(255) NOT NULL COMMENT 'user name',
  `origin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'origin id',
  `info` json DEFAULT NULL COMMENT 'user info',
  `setting` json DEFAULT NULL COMMENT 'user setting',
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `tms-crt` (`tms_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `i_type` tinyint(4) NOT NULL COMMENT 'auth type',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'user id',
  `uaid` varchar(255) NOT NULL COMMENT 'user auth id',
  `code` varchar(255) NOT NULL COMMENT 'auth code',
  `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'expire timeint, 0:infi',
  `extra` json DEFAULT NULL,
  `tms_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tms_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i-u` (`i_type`,`uaid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_keep` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `i_item` int(11) NOT NULL COMMENT 'item id',
  `num` int(11) DEFAULT NULL COMMENT 'number',
  `str` varchar(255) DEFAULT NULL COMMENT 'string',
  `txt` text COMMENT 'text',
  `obj` json DEFAULT NULL COMMENT 'object',
  `tms` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid-item` (`uid`,`i_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `i_event` tinyint(4) NOT NULL COMMENT 'event id',
  `args` json DEFAULT NULL COMMENT 'event args',
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `u-i` (`uid`,`i_event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
