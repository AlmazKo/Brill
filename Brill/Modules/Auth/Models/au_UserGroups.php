<?php
/**
 * Description of au_Activation
 *
 * @author Alexander
 */
class au_UserGroups extends  Model{
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'user_id',
        'group_id'
    );
}