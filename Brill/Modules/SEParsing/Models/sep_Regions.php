<?php
/**
 * Keywords
 *
 * класс ключевых слов
 */

class sep_Regions extends Model {
    const ID_YANDEX_MOSCOW = 213;
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'name',
        'sort',
    );
}

