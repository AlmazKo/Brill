<?php
/**
 * sep_YandexAccess
 * Доступы к ресурсам Yandex
 *
 * класс ключевых слов
 */

class sep_YandexAccesses extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'login',
        'password',
        'xml_key',
        'interface_id',
        'date',
    );
}

