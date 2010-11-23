<?php
/**
 * Urls
 *
 */

class sep_UrlKeywords extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'url',
        'keyword_id',
        'date',
    );
}