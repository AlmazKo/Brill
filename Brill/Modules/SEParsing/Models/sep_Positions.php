<?php
/**
 * Positions
 *
 */

class sep_Positions extends Model {
    protected $_isPk = false;
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'keyword_id',
        'url_id',
        'site_id',
        'pos',
   //     'pos_dot',
        //найден по ссылке
        'links_search',
        'date'
    );
}