<?php
/**
 * Description of Logs
 *
 * @author almaz
 */
class mLogs extends Model {
    protected $_tblName = 'Logs';
    protected $_fields = array (
        'id',
        'class',
        'descr',
        'date'
    );
}

/*
CREATE TABLE `Logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(64) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 */
