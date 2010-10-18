<?php

/**
 * as_Subscribes
 *
 * сводная таблица
 *
 *
 * @author almaz
 */

class as_SitesSubmit {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'site_id',
        'subscribe_id',
        'status',  //  Ok/Fail
        'date',

    );
}