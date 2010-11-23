<?php

/**
 * Description of au_Users
 *
 * @author Alexander
 */
class au_Users extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'loginEmail',
        'password',
        'name',
        'publicEmail',
        'reg_status', //статус ('Yes', 'No')
        'date',
        'hash'
    );
}