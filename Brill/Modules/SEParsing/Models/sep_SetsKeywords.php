<?php
/**
 * Keywords
 *
 * класс ключевых слов
 */

class sep_SetsKeywords extends Model {
    protected $_isPk = array('set_id', 'keyword_id');
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'set_id',
        'keyword_id',
        'url_id',
        'status',
        'date'
    );
}

