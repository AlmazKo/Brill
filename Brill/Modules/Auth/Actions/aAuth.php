<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author Alexander
 */
class aAuth extends Action {
    protected $defaultAct = 'login';
    protected function configure() {
        require_once $this->module->pathModels . 'au_Users.php';
        require_once $this->module->pathModels . 'au_Groups.php';
    }

    function act_Login () {

        if ($this->request->isAjax()) {
            $this->context->setTopTpl('auth_login_form');
        } else {
            $this->context->setTopTpl('auth_login');
            $this->context->setTpl('content', 'auth_login_form');
        }
        $fields = array();
        $fields['login'] = array('title' => 'Логин', 'required' => true, 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['password'] = array('title' => 'Пароль', 'required' => true, 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $form = new oForm($fields);
        $this->context->set('form', $form);

        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $sql = Stmt::prepare2(au_Stmt::GET_USER, array('login' => $form->getFieldValue('login'), 'password' => md5($form->getFieldValue('password'))));
                $result = DBExt::getOneRowSql($sql);
                if ($result) {
                    $user = new au_Users($result['id']);
                    $user->date_last = time();
                    $user->save();
                    $sql = Stmt::prepare2(au_Stmt::GET_GROUPS_USER, array('user_id' => $user->id));
                    //TODO переименовать в fetchTroArray
                    $aGroups = DBExt::selectToArray($sql);

                    $userInfo = array();
                    $userInfo['user'] = $user->toArray();
                    $userInfo['groups'] = $aGroups;

                    $this->session->set('userInfo', $userInfo);
                    $this->context->del('form');
                } else {
                    $this->context->set('error', 'Не найден пользователь');
                }
            } else {
                $this->context->set('error', 'Ошибка авторизации');
            }
        }
        $this->context->set('title', 'Авторизация');
    }

    function act_Logout() {
        General::runEvent(GENERAL::EVENT_LOGOUT);
        Routing::instance()->redirect('Auth/');
    }
}