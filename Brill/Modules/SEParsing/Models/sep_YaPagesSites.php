<?php
/**
 * Sites
 *
 */

class sep_YaPagesSites extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'site_id',
        'status',
        'parse',
        'date_update',
        'date_first',
    );
}