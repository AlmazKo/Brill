<?php
/**
 * Urls
 *
 * класс ссылок
 */

class Urls extends Model {
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'id',
        'url',
        'site_id',
        'date',
    );
}