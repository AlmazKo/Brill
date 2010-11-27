<?php
/**
 * sep_YandexAccess
 * Доступы к ресурсам Yandex
 *
 * класс ключевых слов
 */

class sep_YandexAccess extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'login',
        'password',
        'type', // enum('Proxy','local')
        'xml_key',
        'ip_id',
        'date',
    );
}

