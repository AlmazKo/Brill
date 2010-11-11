<?php
/**
 * Keywords
 *
 * класс ключевых слов
 */

class sep_Regions extends Model {
    const ID_YANDEX_MOSCOW = 213;
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'name',
        'sort',
    );
}

