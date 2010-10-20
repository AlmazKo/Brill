<?php
/**
 * Keywords
 *
 * класс ключевых слов
 */

class sep_keywords extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'name',
        'region_id',
        'set_id',
        'thematic_id',
        'yandex',
        'google',
        'rambler'
    );
    /*
     * сделать еще чтото вроде $extFields - в нем будут хранится настройки полей, и отображения
     */

}

