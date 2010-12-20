<?php

class aYandexAccesses extends Action{
    protected $defaultAct = 'view';

    protected function configure() {
        require_once $this->module->pathModels . 'sep_YandexAccesses.php';

        $sql = Stmt::prepare(se_Stmt::INTERFACES_USUAL);
        $interfaces = new oList(DBExt::selectToList($sql));

        $fields['login'] = array('title' => 'Логин', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'без указания домена @yandex.ru', 'error' => false, 'attr' => '', $checked = array());
        $fields['password'] = array('title' => 'Пароль', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['xml_key'] = array('title' => 'Xml ключ', 'value' => '', 'type'=>'textarea', 'required' => true, 'validator' => null, 'info'=>'Берется на странице http://xml.yandex.ru/stat.xml', 'error' => false, 'attr' => '', $checked = array());
        $fields['interface_id'] = array('title' => 'Интерфейс', 'value' => '', 'data' => $interfaces, 'type'=>'select', 'required' => true, 'validator' => null, 'info'=>'Может быть именем интерфейса, IP адресом или именем хоста', 'error' => false, 'attr' => '', $checked = array());
    
        $this->fields = $fields;
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('yandexAccesses_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'yandexAccesses_html');
        }

        $sql = Stmt::prepare(se_Stmt::YANDEX_ACCESSES);
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->setNamesColumns(array(
            'login'=>'Логин',
            'password' => 'Пароль',
            'xml_key' => 'Xml ключ',
            'interface' => 'Интерфейс',
            ));
        $tbl->addRulesView('password', '******');
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $this->context->set('tbl', $tbl);
    }

    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('edit_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'edit_html');
        }
        $this->context->set('h1', 'Редактирование сетевого интерфейса');
        $id = (int)$this->request->get('id', 0);
        if (!$id) {
            return;
        }
        $form = new oForm($this->fields, array('GET' => array('id' => $id)));
        $yaAccess = new sep_YandexAccesses();

        if ($yaAccess->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $yaAccess->fillObjectFromArray($form->getValues());
                    $yaAccess->save();
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'SEParsing';
                    $iRoute->action = 'YandexAccesses';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
            } else {
                $form->fill($yaAccess->toArray());
                $this->context->set('form', $form);
            }
        }

    }

    function act_Add () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('add_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'add_html');
        }

        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $yaAccess = new sep_YandexAccesses();
                $yaAccess->fillObjectFromArray($form->getValues());
                $yaAccess->save();
                $this->context->del('form');

                $iRoute = new InternalRoute();
                $iRoute->module = 'SEParsing';
                $iRoute->action = 'YandexAccesses';
                $actR = new ActionResolver();
                $act = $actR->getInternalAction($iRoute);
                $act->runAct();
            }
        }
        $this->context->set('h1', 'Добавление новых доступов к yandex.ru');
    }

    function act_Del () {
        $yaAccess = new sep_YandexAccesses((int)$this->request->get('id'));
        $yaAccess->delete();
        $iRoute = new InternalRoute();
        $iRoute->module = 'SEParsing';
        $iRoute->action = 'YandexAccesses';
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

