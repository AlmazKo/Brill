<?php

class aSets extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_Sets.php';
        $fields['name'] = array('title' => 'Название сета (профиля позиций)', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $this->fields = $fields;
    }

    function act_Add () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('sets_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'sets_edit');
        }
        $id = (int)$this->request->get('id', 0);
        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $sets = new sep_Sets();
                    $sets->fillObjectFromArray($form->getValues());
                    $sets->save();
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'SEParsing';
                    $iRoute->action = 'Sets';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
        }
        $this->context->set('h1', 'Создание нового сета');
    }


    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('sets_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'sets_edit');
        }
        $this->context->set('h1', 'Редактирование сета', false);
        $id = (int)$this->request->get('id', 0);
        $form = new oForm($this->fields, array('GET' => array('id' => $id)));
        $sets = new sep_Sets();

        if ($sets->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $sets->fillObjectFromArray($form->getValues());
                    $sets->save();
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'SEParsing';
                    $iRoute->action = 'Sets';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
            } else {
                $form->fill($sets->toArray());
                $this->context->set('form', $form);
            }
        }
        
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('sets_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'sets_html');
        }
        $sets = new sep_Sets();
        $tbl = new oTableExt(array($sets->getFields(), $sets->getArray()));
        $tbl->viewColumns('name');
        $tbl->setNamesColumns(array('name'=>'Сет'));
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $tbl->setCustomOpt('Stat', 'Просмотреть статистику по сету', 'charts.png', 'Keywords', 'Stat', 'set_id');
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?set_id=#id#">#name#</a>');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
    }


    function act_Del () {
        $id = (int)$this->request->get('id', 0);
        $sql = Stmt::prepare(se_Stmt::ALL_KEYWORDS_SET, array('s_id' => $id, Stmt::LIMIT => 1));
        if (DBExt::isData($sql)) {
            $this->context->set('error', 'Нельзя удалить сет пока в нем есть ключевики');
        } else {
            $sets = new sep_Sets((int)$this->request->get('id'));
            $sets->delete();
        }

        $iRoute = new InternalRoute();
        $iRoute->module = 'SEParsing';
        $iRoute->action = 'Sets';
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

