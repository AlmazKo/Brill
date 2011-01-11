<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegistryParser
 *
 * @author asuslov
 */

class RegistryParser extends Registry{

    protected static $instance = null;
    final public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }
    protected function  __construct() {
        $this->set('ip', null); //������� ������
        $this->set('path_to_cookies', DIR_PATH . '/tmp/cookies');
        $this->set('_get', '');
        $this->set('_post', null); //post �� ������������
    }


}
?>
