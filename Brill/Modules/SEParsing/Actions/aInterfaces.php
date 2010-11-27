<?php

class aInterfaces extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_Interfaces.php';

        $ports = new oList(array(array('80', '80'), array('8080', '8080')));
        $types = new oList(array(array('Proxy', 'Прокси'), array('Usual', 'Обычный')));
        $proxyTypes = new oList(array(array('HTTP', 'HTTP'), array('SOCKS5', 'SOCKS5')));

        $fields['interface'] = array('title' => 'Cетевой интерфейс', 'value' => '', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'Может быть именем интерфейса, IP адресом или именем хоста', 'error' => false, 'attr' => '', $checked = array());
        $fields['port'] = array('title' => 'Порт', 'value' => '', 'data' => $ports, 'type'=>'select', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['type'] = array('title' => 'Тип', 'value' => '', 'data' => $types, 'type'=>'select', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['proxy_type'] = array('title' => 'Тип прокси', 'value' => '', 'data' => $proxyTypes, 'type'=>'select', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['proxy_login'] = array('title' => 'Логин от прокси', 'value' => '', 'type'=>'text', 'requried' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['proxy_password'] = array('title' => 'Пароль от прокси', 'value' => '', 'type'=>'text', 'requried' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());

        $this->fields = $fields;
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('interfaces_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'interfaces_html');
        }
        $ints = new sep_Interfaces();
        $tbl = new oTableExt(array($ints->getFields(), $ints->getArray()));
        $tbl->setNamesColumns(array(
            'interface'=>'Интерфейс',
            'port' => 'Порт',
            'type' => 'Тип',
            'proxy_type' => 'Тип прокси',
            'proxy_login' => 'Логин от прокси',
            'proxy_password' => 'Пароль от прокси',
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
        $ints = new sep_Interfaces();

        if ($ints->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $ints->fillObjectFromArray($form->getValues());
                    $ints->save();
                    $this->context->del('form');
                }
            } else {
                $form->fill($ints->toArray());
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

