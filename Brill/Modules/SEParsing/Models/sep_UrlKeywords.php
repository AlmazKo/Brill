<?php
/**
 * Urls
 *
 */

class sep_UrlKeywords extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'url',
        'keyword_id',
        'date',
    );
}