<?php

/**
 * as_Subscribes
 *
 * класс с информацией о рассылках
 *
 * @author almaz
 */

class as_Subscribes extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'user_id',
        'name', //название рассылки
        'form', //форма в виде xml
        'date_created', // дата создания рассылки
        'date_begin', //дата начала рассылки
        'date_end', // дата конца рассылки
    );
}