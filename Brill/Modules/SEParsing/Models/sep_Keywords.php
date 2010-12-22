<?php
/**
 * Keywords
 *
 * класс ключевых слов
 */

class sep_Keywords extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'name',
        'region_id',
        'set_id',
        'thematic_id',
        'url_id'
    );
    /*
     * сделать еще чтото вроде $extFields - в нем будут хранится настройки полей, и отображения
     */

}

