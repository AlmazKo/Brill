<?php
/**
 * sep_Interfaces
 * Количество вызовов в этот день
 */

class sep_InterfaceCountCallToday extends Model {
    protected $_isPk = false;
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'interface_id',
        'host_id',
        'count',
        'last_date',
    );
}

