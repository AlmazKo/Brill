<?php
/**
 * as_Sites
 *
 * класс Сайтов
 */
class as_Sites extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'host',
        'date',
        'config_status', //статус конфига enum ('None', 'Yes', 'Edit') есть/нет/есть но не работоспособен
        'rule'
    );
}

/*
CREATE TABLE `as_Sites` (
  `id` smallint(5) unsigned NOT NULL,
  `host` varchar(64) DEFAULT NULL,
  `config_status` enum('None','Yes','Edit') DEFAULT 'None',
  `date` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `host_UNIQUE` (`host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */

/*
<?php

class AS_Site{
    public $host = null;
    public $rulefilexml = null;
    public $currentRule = null;

    public function   __construct() {
        $this->host = 'www.bcounter.ru';
        $this->rulefilexml = './SITE_RULES/bcounter.xml';
        $this->currentRule = $_SESSION['currentRule'];
    }
}
 *
 */
