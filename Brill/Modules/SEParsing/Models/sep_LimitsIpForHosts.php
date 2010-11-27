<?php
/**
 * sep_Interfaces
 * Все IP и прокси
 */

class sep_Interfaces extends Model {
    protected $_tblName = __CLASS__;
    protected $_fields = array (
        'id',
        //IP address or a host name
        'interface',
        'port',
        'type', // enum('Proxy','local')
        'proxy_type', //enum ('HTTP', 'SOCKS5')
        'proxy_password',
        'proxy_login',
        'date',
    );
}

