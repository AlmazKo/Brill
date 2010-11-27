<?php
/**
 * sep_Interfaces
 * Количество вызовов в этот день
 */

class sep_InterfaceCountCallToday extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'interface_id',
        'host_id',
        'count',
        'last_date',
    );
}

