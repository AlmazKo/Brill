<?php
/**
 * sep_Interfaces
 * Лимиты на количество запросов с одного ip
 */

class st_LimitsIpForHosts extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'host_id',
        'every_day',
        'every_hour',
        'every_min',
        'date',
    );
}

