<?php
/**
 * Sites
 *
 */
require_once 'Model.php';
class sep_thematics extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'name',
        'date',
    );
}