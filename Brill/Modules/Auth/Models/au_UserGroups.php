<?php
/**
 * Description of au_Activation
 *
 * @author Alexander
 */
class au_UserGroups extends  Model{
    protected $tbl_name = __CLASS__;
    protected $fields = array (
        'user_id',
        'group_id'
    );
}