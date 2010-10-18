<?php

/**
 * as_Subscribes
 *
 * класс с информацией о рассылках
 *
 * @author almaz
 */

class as_Subscribes {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'user_id',
        'name', //название рассылки
        'form_id', //ссылка на форму
        'date_begin', //дата начала рассылки
        'date_end', // дата конца рассылки
    );
}