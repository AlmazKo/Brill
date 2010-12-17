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
        $session = RegistrySession::instance();
        return $session->is('userInfo') && $session->get('userInfo');
    }

    function isAdmin (){
        if ($this->isLogin()) {
            $session = RegistrySession::instance();
            $userInfo = $session->get('userInfo');
            if (array_key_exists(General::GROUP_ADMIN, $userInfo['groups'])) {
                return false;
            }
        }

        return false;
    }

    function e_Logout() {
        $session = RegistrySession::instance();
        $session->del('userInfo');
    }

    function userInfo() {}

    function login() {

    }

    private function _checkEmailAndPasswd() {}

    function send() {}

    function addNewbie() {}

    function isValidLink() {}
    function getGroups() {}

    /**
     * Проверка прав доступа пользователя на конкретный Act
     * @return bool
     */
    public function e_beforeRunAct() {
        $route = Routing::instance();
        if(!('Auth' == $route->module && 'Auth' == $route->action)) {
            if ($this->isAdmin()) {
                return true;
            }
            $session = RegistrySession::instance();
            $module = General::getCurrentModule();
            $accessModules = $module->getSettingsAccess();
            $userInfo = $session->get('userInfo');
            $access = false;
            if ($accessModules) {
                foreach($accessModules as $groupId => $action) {
                   if (isset($userInfo['groups'][$groupId]) && in_array($route->act, $action[$route->action])) {
                       $access = true;
                       break;
                   }
                }
                if (!$access) {
                    die ('У вас нет прав на это действие '.$route->act);
                }
            }
        }
    }

    /**
     * Проверка прав доступа пользователя на конкретный Action
     * @return bool
     */
    public function  e_InitAction() {
        $route = Routing::instance();
        $request = RegistryRequest::instance();
        if ('Auth' != $route->module && 'Auth' != $route->action && !$this->isLogin()) {
            if($request->isAjax()) {
                die ('Нажмите F5');
            } else {
                $route->redirect('Auth/');
            }
        } else if(!('Auth' == $route->module && 'Auth' == $route->action)) {
           
            if ($this->isAdmin()) {
                return true;
            }
            $session = RegistrySession::instance();
            $module = General::getCurrentModule();
            $accessModules = $module->getSettingsAccess();

            $userInfo = $session->get('userInfo');
            $access = false;
            if ($accessModules) {
                foreach($accessModules as $groupId => $action) {
                   if (isset($userInfo['groups'][$groupId]) && isset($action[$route->action])) {
                       $access = true;
                       break;
                   }
                }
               
            } else {
                    die ('У вас нет прав на этот Раздел');
                }
        } 
    }
}