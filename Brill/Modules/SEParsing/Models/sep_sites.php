<?php
/**
 * Sites
 *
 */

class sep_sites extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'name',
        'date',
        'domain_create',
        'domain_id',
       
    );
}