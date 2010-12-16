<?php

class aUsers extends Action{
    protected $defaultAct = 'list';
    protected function configure() {
        require_once $this->module->pathModels . 'au_Users.php';

        $sql = Stmt::prepare2(au_Stmt::GET_LIST_GROUPS);
        $groups = new oList(DBExt::selectToList($sql));
        $groups->setMulti();
        $fields['groups'] = array('title' => 'Группы', 'value' => '', 'data' => $groups, 'type'=>'select', 'required' => true, 'validator' => null, 'info'=>'Можно выбрать несколько значений', 'error' => false, 'attr' => 'multiple="multiple" size="4"', $checked = array());
        $fields['name'] = array('title' => 'Имя', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['login'] = array('title' => 'Логин', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['password'] = array('title' => 'Пароль', 'value' => '', 'type'=>'text', 'required' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $this->fields = $fields;
    }

    function act_Add () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('edit_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'edit_html');
        }
        $this->fields['password']['required'] = true;
        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $user = new au_Users();
                $user->fillObjectFromArray($form->getValues());
                $user->password = md5($user->password);
                $user->status = 'Active';
                $user->save();
                $groups = $form->getField('groups');
                $listGroups = $groups['data'];
                $aGroups = $listGroups->getSelected();
                foreach($aGroups as $groupId) {
                     $sql = Stmt::prepare2(au_Stmt::ADD_USERS_GROUPS, array('user_id'=>$user->id, 'group_id' => $groupId));
                     DB::query($sql);
                }
                $this->context->del('form');
                $iRoute = new InternalRoute();
                $iRoute->module = 'Auth';
                $iRoute->action = 'Users';
                $actR = new ActionResolver();
                $act = $actR->getInternalAction($iRoute);
                $act->runAct();
            }
        }
        $this->context->set('h1', 'Добавление нового пользователя');
    }


    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('edit_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'edit_html');
        }
        $this->context->set('h1', 'Редактирование пользователя', false);
        $id = (int)$this->request->get('user_id', 0);
$this->fields['password']['info'] = 'Если оставить поле пустым, пароль останется прежним';
        $form = new oForm($this->fields, array('GET' => array('user_id' => $id)));
        $user = new au_Users();

        if ($user->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                $password = $user->password;
                if ($form->isComplited()) {
                    $user->fillObjectFromArray($form->getValues());
                    $user->status = 'Active';
                    if ('' === $user->password) {
                        $user->password = $password;
                    } else {
                        $user->password = md5($user->password);
                    }

                    $user->save();

                    DB::query(Stmt::prepare2(au_Stmt::DEL_USERS_GROUPS_USER, array('user_id' => $user->id)));
                    $groups = $form->getFieldValue('groups');
                    foreach($groups as $groupId) {
                         $sql = Stmt::prepare2(au_Stmt::ADD_USERS_GROUPS, array('user_id'=>$user->id, 'group_id' => $groupId));
                         DB::query($sql);
                    }
                    
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'Auth';
                    $iRoute->action = 'Users';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
            } else {
                $sql = Stmt::prepare2(au_Stmt::GET_GROUPS_USER, array('user_id' => $user->id));
                $aGroups = DBExt::selectToList($sql);
                $user->password = '';
                $form->fill($user->toArray() + array('groups' => array_keys($aGroups)));
                $this->context->set('form', $form);
            }
        }

    }

    function act_List () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('list_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'list_html');
        }

        $this->context->set('h1', 'Все пользователи');
        $sql = Stmt::prepare2(au_Stmt::GET_ALL_USER);
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->viewColumns('user_login', 'user_name', 'status', 'groups');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->setViewIterator(true);

        $tbl->setNamesColumns(array('user_login'=>'Логин', 'user_name' => 'Имя', 'status' => 'Статус', 'groups' => 'Группы'));
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
    }


    function act_Del () {
        $id = (int)$this->request->get('user_id', 0);
        $user = new au_Users();

        if ($user->getObject($id)) {
            $user->status = 'Deleted';
            $user->save();
            $this->context->setMessage('Пользователь удален');
        } else {
            $this->context->setError('Не существует такого пользователя');
        }

        $iRoute = new InternalRoute();
        $iRoute->module = 'Auth';
        $iRoute->action = 'Users';
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($iRoute);
        $act->runAct();
    }
    function _parent(InternalRoute $iRoute = null) {
        if (!$iRoute) {
            $iRoute = new InternalRoute();
            $iRoute->module = 'Pages';
            $iRoute->action = 'Pages';
        }
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($iRoute);
        $act->runParentAct();
    }
}
