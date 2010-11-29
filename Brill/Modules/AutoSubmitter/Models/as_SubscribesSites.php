<?php
/**
 * as_SubscribesSites
 *
 * класс сводной таблицы рассылок и Сайтов
 */
class as_SubscribesSites extends Model {
    protected $_isPk = false;
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'subscribe_id',
        'site_id',
        'status'
    );
}

/*
CREATE TABLE `as_SubscribesSites` (
  `subscribe_id` mediumint(8) unsigned NOT NULL,
  `site_id` smallint(5) unsigned NOT NULL,
  `status` enum('No','Yes','Busy') NOT NULL DEFAULT 'No',
  UNIQUE KEY `uniq` (`site_id`,`subscribe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */