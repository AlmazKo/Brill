<?php
/**
 * Sites
 *
 * класс сетов ключевиков
 */
require_once 'Model.php';
class Sets extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'name',
        'date',
    );
}