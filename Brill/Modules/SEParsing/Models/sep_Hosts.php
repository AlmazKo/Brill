<?php
/**
 * sep_Hosts
 * сервисы, которые парсим
 */

class sep_Hosts extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'name',
        'url',
        'descr',
        'date',
    );
}

