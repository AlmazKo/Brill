<?php
/**
 * Sites
 *
 */

class sep_Sites extends Model {
    const TABLE = 'sep_Sites';
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'name',
        'date',
        'domain_create',
        'domain_id',
       
    );
}