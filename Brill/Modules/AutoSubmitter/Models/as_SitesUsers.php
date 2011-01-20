<?php
/**
 * as_SubscribesSites
 *
 * класс сводной таблицы рассылок и Сайтов
 */
class as_SitesUsers extends Model {
    protected $_isPk = array('site_id', 'user_id');
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'site_id',
        'user_id',
        'login',
        'password',
        'update'
    );
}

/*
CREATE TABLE `as_SitesUsers` (
  `site_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `login` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`site_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */