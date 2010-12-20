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

    /*
     * Список исключений, к которым не применяются проверка прав доступа
     * каждое исключение имеет вид: array(NameModule, NameAction)
     */
    private $_exclude = array(array('Auth', 'Auth'), array('Pages', 'Error'));
    /**
     * Проверяем авторизхован ли пользователь
     * @return bool
     */
    function isLogin (){
        $session = RegistrySession::instance();
        return $session->is('userInfo') && $session->get('userInfo');
    }

    /**
     * Проверка надо ли применять правили проверки достпа
     * @param string $moduleName 
     * @param string $actionName
     * @return bool
     */
    function isExclude($moduleName, $actionName) {
        foreach ($this->_exclude as $exclude) {
            if ($exclude[0] == $moduleName && $exclude[1] == $actionName) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Есть ли у текущего пользователя админские права
     * @return bool
     */
    function isAdmin (){
        if ($this->isLogin()) {
            $session = RegistrySession::instance();
            $userInfo = $session->get('userInfo');
            if (array_key_exists(General::GROUP_ADMIN, $userInfo['groups'])) {
                return true;
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
        if(!$this->isExclude($route->module, $route->action)) {
            if ($this->isAdmin()) {
                return;
            }
            $session = RegistrySession::instance();
            $module = General::getCurrentModule();
            $accessModules = $module->getSettingsAccess();
            $userInfo = $session->get('userInfo');
            $access = false;
            if ($accessModules) {
                foreach($accessModules as $groupId => $action) {
                   if (isset($userInfo['groups'][$groupId]) && isset($action[$route->action]) &&
                       in_array($route->act, $action[$route->action])) {
                       $access = true;
                       break;
                   }
                }
                if (!$access) {
                   return new Error('У вас нет прав на это действие '.$route->act);
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
        if (!$this->isExclude($route->module, $route->action)) {
            if (!$this->isLogin()) {
                $route->redirect('Auth/');
            }
            if ($this->isAdmin()) {
                return;
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
                if (!$access) {
                    return new Error('У вас нет прав на этот Раздел');
                }
            } else {
                return new Error('У вас нет прав на этот Раздел');
            }
        }
    }
}