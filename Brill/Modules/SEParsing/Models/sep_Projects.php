<?php
/**
 * sep_Projects
 * Проекты
 *
 * класс ключевых слов
 */

class sep_Projects extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'name',
        'site',
        'descr',
        'status',
        'date_create',
        'site_id'
    );
}

