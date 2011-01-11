<?php

/**
 * as_Subscribes
 *
 * класс с информацией о рассылках
 *
 * @author almaz
 */

class as_Subscribes extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'user_id',
        'name', //название рассылки
        'form', //форма в виде xml
        'date_created', // дата создания рассылки
        'date_begin', //дата начала рассылки
        'date_end', // дата конца рассылки
    );
}

/*
 CREATE TABLE `as_Subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `form` blob NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_begin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8
 */