<?php
/**
 * as_Sites
 *
 * класс Сайтов
 */
class mPages extends Model {
    protected $tbl_name = 'Pages';
    protected $fields = array (
        'id',
        'name',
        'content',
        'date'
    );
}
/*
 CREATE TABLE `Pages` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `date` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 */