<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Acl
 *
 * @author Alexander
 */
class UserLib extends Lib {
    private $_isLogin;
    private $_groups;

    /**
     * Проверяем авторизхован ли пользователь
     * @return bool
     */
    function isLogin (){
        return true;
    }

    function logout() {}

    function userInfo() {}

    function login() {}

    private function _checkEmailAndPasswd() {}

    function send() {}

    function addNewbie() {}

    function isValidLink() {}
    function getGroups() {}

    public function  e_InitAction() {
        #parent::initAction();
        if (!$this->isLogin()) {
            $this->isLogin = false;
           //         $route->redirect('Brill/static/error.html'); // редирект на страницу авторизации
        }

    }
}
