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
    private $_session;
    private $_isLogin;
    private $_groups;

    /**
     * Проверяем авторизхован ли пользователь
     * @return bool
     */
    function isLogin (){
//        if (isset($session['user'])) {
//
//        } else {
//            return false;
//        }

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


    public function e_beforeRunAct() {
        $module = General::getCurrentModule();
        $groups = $module->getSettingsAccess();
        foreach($groups as $name => $settings) {

        }
    }
    public function  e_InitAction() {
        $session = RegistrySession::instance();
        if ($this->isLogin($session)) {

        }
        //если ли право на экшен меня и/или моей группы
        //если нет - редиреект
        //если право на экт
        //редирект на главную
        //
        #parent::initAction();
        if (!$this->isLogin()) {
            $this->isLogin = false;
           //         $route->redirect('Brill/static/error.html'); // редирект на страницу авторизации
        }

    }
}