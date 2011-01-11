<?php

/**
 * Description of au_Users
 *
 * @author Alexander
 */
class au_Users extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        'login',
        'password',
        'name',
        'group_id',
        'status', //статус ('Yes', 'No')
        'date_last',
        'date_created',
        //хэш при проверке через почту. после удачной авторизации должен сбрасываться
      //  'auth_hash'
    );
}

/*


 */