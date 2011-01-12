<?php
/**
 * as_SubscribesSites
 *
 * класс сводной таблицы рассылок и Сайтов
 */
class as_SubscribesSites extends Model {
    protected $_isPk = array('subscribe_id', 'site_id');
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'subscribe_id',
        'site_id',
        'status',
        'form',
        'rule_num',
        'is_skip'
    );
}

/*
CREATE TABLE `as_SubscribesSites` (
  `subscribe_id` mediumint(8) unsigned NOT NULL,
  `site_id` smallint(5) unsigned NOT NULL,
  `status` enum('No','Ok','Busy','Error') NOT NULL DEFAULT 'No',
  `form` blob NOT NULL,
  `rule_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_skip` enum('No','Yes') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`subscribe_id`,`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */