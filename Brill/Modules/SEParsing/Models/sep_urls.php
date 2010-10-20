<?php
/**
 * Urls
 *
 */

class sep_urls extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'url',
        'site_id',
        'date',
    );
}