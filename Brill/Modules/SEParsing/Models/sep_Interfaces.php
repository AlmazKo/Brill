<?php
/**
 * sep_Interfaces
 * Лимиты на количество запросов с одного ip
 */

class sep_LimitsIpForHosts extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'host_id',
        'everyDay',
        'everyHour',
        'everyMin',
        'date',
    );
}

