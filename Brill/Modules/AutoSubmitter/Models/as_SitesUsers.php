<?php
/**
 * as_Sites
 *
 * класс Сайтов
 */
class as_SitesUsers extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'site_id',
        'user_id',
        'login',
        'password', //статус конфига enum ('None', 'Yes', 'Edit') есть/нет/есть но не работоспособен
        'date'
    );
}
