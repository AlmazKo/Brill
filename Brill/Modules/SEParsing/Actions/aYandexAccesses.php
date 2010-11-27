<?php

class aYandexAccesses extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_YandexAccesses.php';

        $ips = new oList(array(array('1', '89.249.22.228')));

        $fields['login'] = array('title' => 'Логин', 'value' => '', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'без указания домена @yandex.ru', 'error' => false, 'attr' => '', $checked = array());
        $fields['password'] = array('title' => 'Пароль', 'value' => '', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'Может быть именем интерфейса, IP адресом или именем хоста', 'error' => false, 'attr' => '', $checked = array());
        $fields['xml_key'] = array('title' => 'Xml ключ', 'value' => '', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'Может быть именем интерфейса, IP адресом или именем хоста', 'error' => false, 'attr' => '', $checked = array());
        $fields['ip_id'] = array('title' => 'Интерфейс', 'value' => '', 'data' => $ips, 'type'=>'select', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
    
        $this->fields = $fields;
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('yandexAccesses_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'yandexAccesses_html');
        }
        $yaAccess = new sep_YandexAccesses();
        $tbl = new oTableExt(array($yaAccess->getFields(), $yaAccess->getArray()));
        $tbl->setNamesColumns(array(
            'login'=>'Логин',
            'password' => 'Пароль',
            'xml_key' => 'Xml ключ',
            'ip_id' => 'Интерфейс',
            ));
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $this->context->set('tbl', $tbl);
    }

    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('interfaces_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'interfaces_edit');
        }
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
                }
            } else {
                $form->fill($yaAccess->toArray());
                $this->context->set('form', $form);
            }
        }
        $this->context->set('h1', 'Редактирование сетевого интерфейса');
    }

    function act_Add () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('interfaces_add');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'interfaces_add');
        }

        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $ints = new sep_Interfaces();
                $ints->fillObjectFromArray($form->getValues());
                $ints->save();
                $this->context->del('form');
            }
        }
        $this->context->set('h1', 'Добавление нового сетевого интерфейса');
    }

    function act_Del () {
        $ints = new sep_Interfaces((int)$this->request->get('id'));
        $ints->delete();
        $iRoute = new InternalRoute();
        $iRoute->module = 'SEParsing';
        $iRoute->action = 'Interfaces';
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

