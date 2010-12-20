<?php

class aInterfaces extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'st_Interfaces.php';

        $ports = new oList(array('80' => '80', '8080' => '8080'));
        $types = new oList(array('Proxy' => 'Прокси', 'Usual' => 'Обычный'));
        $proxyTypes = new oList(array('HTTP' => 'HTTP', 'SOCKS5' => 'SOCKS5'));

        $fields['interface'] = array('title' => 'Cетевой интерфейс', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'Может быть именем интерфейса, IP адресом или именем хоста', 'error' => false, 'attr' => '', $checked = array());
        $fields['port'] = array('title' => 'Порт', 'value' => '', 'data' => $ports, 'type'=>'select', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['type'] = array('title' => 'Тип', 'value' => '', 'data' => $types, 'type'=>'select', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['proxy_type'] = array('title' => 'Тип прокси', 'value' => '', 'data' => $proxyTypes, 'type'=>'select', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['proxy_login'] = array('title' => 'Логин от прокси', 'value' => '', 'type'=>'text', 'required' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['proxy_password'] = array('title' => 'Пароль от прокси', 'value' => '', 'type'=>'text', 'required' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $this->fields = $fields;
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('list_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'interfaces_html');
        }
        $ints = new st_Interfaces();
        $tbl = new oTableExt(array($ints->getFields(), $ints->getArray()));
        $tbl->setNamesColumns(array(
            'interface'=>'Интерфейс',
            'port' => 'Порт',
            'type' => 'Тип',
            'proxy_type' => 'Тип прокси',
            'proxy_login' => 'Логин от прокси',
            'proxy_password' => 'Пароль от прокси',
            ));
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $tbl->addRulesView('proxy_password', '******');
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $tbl->viewColumns('interface', 'port', 'type');
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Интерфейсы');
    }

    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('edit_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'edit_html');
        }
        $id = (int)$this->request->get('id', 0);
        $form = new oForm($this->fields, array('GET' => array('id' => $id)));
        $ints = new st_Interfaces();
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
                $ints = new st_Interfaces();
                $ints->fillObjectFromArray($form->getValues());

                $ints->save();
                $this->context->del('form');
            }
        }
        $this->context->set('h1', 'Добавление нового сетевого интерфейса');
    }

    function act_MassAdd () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('edit_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'edit_html');
        }
        $this->fields['interface'] = array('title' => 'Список интерфейсов', 'value' => '', 'type'=>'textarea', 'required' => true, 'validator' => null,
            'info'=>'Разделитель - новая строка.<br />Может содержать номер порта после двоеточия.<br />Например: 192.168.1.1:8080<br /> Интерфейсам, у которых не указан порт, <br /> порт присвоится из указанного значения снизу.', 'error' => false, 'attr' => 'rows="10"', $checked = array());
        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $ints = new st_Interfaces();
                $ints->fillObjectFromArray($form->getValues());
                $port = $ints->port;
                $interfaces = TFormat::winTextToLinux($this->request->get('interface'));
                $aInts = explode("\n", $interfaces);
                if (is_array($aInts)) {
                    foreach ($aInts as $value) {
                        $interface = explode(":", $value, 2);
                        if (1 == count($interface)) {
                            $ints->interface = $value;
                            $ints->port = $port;
                        } else {
                            $ints->interface = $interface[0];
                            $ints->port = (int)$interface[1];
                        }
                        $ints->save()->reset();
                    }
                }
            }
        }
        $this->context->set('h1', 'Массовое добавление сетевых интерфейсов');
    }

    function act_Del () {
        $ints = new st_Interfaces((int)$this->request->get('id', 0));
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

