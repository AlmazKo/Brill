<?php
/**
 * Urls
 *
 */

class sep_Urls extends Model {
    const TABLE = 'sep_Urls';
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'url',
        'site_id',
        'date',
        'mime_type',
        'status'
    );
}