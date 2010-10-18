<?php

/**
 * Description of au_Users
 *
 * @author Alexander
 */
class au_Users extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
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