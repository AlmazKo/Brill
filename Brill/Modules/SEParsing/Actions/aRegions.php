<?php

class aRegions extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_Regions.php';
        $fields['name'] = array('title' => 'Название Региона', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['sort'] = array('title' => 'Вес в списке, учитывается при сортировке', 'value' => '', 'type'=>'text', 'required' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['yandex_id'] = array('title' => 'Id региона в yandex\'e', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $this->fields = $fields;
    }

    function act_Add () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('regions_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'regions_edit');
        }

        $form = new oForm($this->fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $regions = new sep_Regions();
                $regions->fillObjectFromArray($form->getValues());
                $regions->save();
                $this->context->del('form');
                $iRoute = new InternalRoute();
                $iRoute->module = 'SEParsing';
                $iRoute->action = 'Regions';
                $actR = new ActionResolver();
                $act = $actR->getInternalAction($iRoute);
                $act->runAct();
            }
        }
        $this->context->set('h1', 'Добавление нового региона');
    }


    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('regions_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'regions_edit');
        }
        $this->context->set('h1', 'Редактирование региона', false);
        $id = (int)$this->request->get('id', 0);
        
        $form = new oForm($this->fields, array('GET' => array('id' => $id)));
        $regions = new sep_Regions();

        if ($regions->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $regions->fillObjectFromArray($form->getValues());
                    $regions->save();
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

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('regions_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'regions_html');
        }
        $regions = new sep_Regions();
        $tbl = new oTableExt(array($regions->getFields(), $regions->getArray()));
        $tbl->setNamesColumns(array('name'=>'Регион'));
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

