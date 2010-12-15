<?php

class aGroups extends Action{
    protected $defaultAct = 'list';
    protected function configure() {
        require_once $this->module->pathModels . 'au_Groups.php';
        $fields['name'] = array('title' => 'Название', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['login'] = array('title' => 'Описание', 'value' => '', 'type'=>'textarea', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => 'rows=4', $checked = array());
        $this->fields = $fields;
    }

    function act_Add () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('edit_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'edit_html');
        }

        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $users = new au_Users();
                $users->fillObjectFromArray($form->getValues());
                $users->save();
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
        $id = (int)$this->request->get('id', 0);
        
        $form = new oForm($this->fields, array('GET' => array('id' => $id)));
        $regions = new sep_Regions();

        if ($regions->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $users->fillObjectFromArray($form->getValues());
                    $users->save();
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'SEParsing';
                    $iRoute->action = 'Regions';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
            } else {
                $form->fill($regions->toArray());
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
        $tbl->viewColumns('user_login', 'user_name', 'status', 'group_name');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->setViewIterator(true);

        $tbl->setNamesColumns(array('user_login'=>'Логин', 'user_name' => 'Имя', 'status' => 'Статус', 'group_name' => 'Группы'));
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
    }


    function act_Del () {
        $id = (int)$this->request->get('id', 0);
        $sql = Stmt::prepare(se_Stmt::KEYWORDS_BY_REGION_YANDEX, array('r_id' => $id, Stmt::LIMIT => 1));
        if (DBExt::isData($sql)) {
            $this->context->set('error', 'Нельзя удалить сет пока в нем есть ключевики');
        } else {
            $regions = new sep_Regions((int)$this->request->get('id'));
            $regions->delete();
        }
        $iRoute = new InternalRoute();
        $iRoute->module = 'SEParsing';
        $iRoute->action = 'Regions';
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

