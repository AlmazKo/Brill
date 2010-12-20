<?php
/**
 * st_Hosts
 * сервисы, которые парсим
 */

class st_Hosts extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'name',
        'url',
        'descr',
        'date',
    );
}

