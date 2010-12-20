<?php

class aThematics extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_Thematics.php';
        $this->context->set('title', 'Тематики');
        $fields['name'] = array('title' => 'Название тематики', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $this->fields = $fields;
    }



    function act_View () {
        
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('thematics_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'thematics_html');
        }
        $thematics = new sep_Thematics();
        $tbl = new oTableExt(array($thematics->getFields(), $thematics->getArray()));
        $tbl->viewColumns('name');
        $tbl->setNamesColumns(array('name'=>'Тематика'));
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?thematic_id=#id#"  ajax="1">#name#</a>');
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $tbl->setViewIterator(true);
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('h1', 'Все тематики');
        $this->context->set('tbl', $tbl);
    }

    function act_Add () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('thematics_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'thematics_edit');
        }
        $id = (int)$this->request->get('id', 0);
        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $thematics = new sep_Thematics();
                    $thematics->fillObjectFromArray($form->getValues());
                    $thematics->save();
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'SEParsing';
                    $iRoute->action = 'Thematics';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
        }
        $this->context->set('h1', 'Добавление новой тематики');
    }

    function act_Del () {
        $id = (int)$this->request->get('id', 0);
        $sql = Stmt::prepare(se_Stmt::ALL_KEYWORDS_THEMATIC, array('t_id' => $id, Stmt::LIMIT => 1));
        if (DBExt::isData($sql)) {
            $this->context->set('error', 'Нельзя удалить тематику пока в ней есть ключевики');
        } else {
            $thematics = new sep_Thematics((int)$this->request->get('id'));
            $thematics->delete();
        }
        
        $iRoute = new InternalRoute();
        $iRoute->module = 'SEParsing';
        $iRoute->action = 'Thematics';
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($iRoute);
        $act->runAct();
    }
    
    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('thematics_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'thematics_edit');
        }
        $id = (int)$this->request->get('id', 0);
        if (!$id) {
            return;
        }
        $form = new oForm($this->fields, array('GET' => array('id' => $id)));
        $thematics = new sep_Thematics();

        if ($thematics->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $thematics->fillObjectFromArray($form->getValues());
                    $thematics->save();
                    $this->context->del('form');

                    $iRoute = new InternalRoute();
                    $iRoute->module = 'SEParsing';
                    $iRoute->action = 'Thematics';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
            } else {
                $form->fill($thematics->toArray());
                $this->context->set('form', $form);
            }
        }
        $this->context->set('h1', 'Редактирование сетевого интерфейса');
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

