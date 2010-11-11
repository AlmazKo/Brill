<?php
/**
 * Positions
 *
 */

class sep_Positions extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'keyword_id',
        'url_id',
        'site_id',
        'pos',
        'pos_dot',
        'links_search',
        'date'
    );
}