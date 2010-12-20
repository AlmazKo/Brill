<?php
/**
 * sep_Interfaces
 * Количество вызовов за все время
 */

class st_InterfaceCountCall extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'interface_id',
        'host_id',
        'count',
        'date',
    );
}

