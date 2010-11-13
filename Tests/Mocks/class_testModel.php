<?php
/**
 * Description of classs_testModel
 * класс заглушка
 * простой наследник Model
 * @author asuslov
 */
class class_testModel extends Model{
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'name',
        'date'
    );
}
?>
