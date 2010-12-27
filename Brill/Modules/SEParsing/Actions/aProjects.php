<?php

class aProjects extends Action{
    protected $defaultAct = 'view';

    protected function configure() {
        require_once $this->module->pathModels . 'sep_Projects.php';
        require_once $this->module->pathModels . 'sep_Sites.php';
        $fields['name'] = array('title' => 'Название', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['site'] = array('title' => 'Полное название сайта', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'Адрес сайта, именно как раскручивается', 'error' => false, 'attr' => '', $checked = array());
        $fields['descr'] = array('title' => 'Описание', 'value' => '', 'type'=>'textarea', 'required' => false, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());

        $this->fields = $fields;
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('list_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'list_html');
        }
        $projects = new sep_Projects();
        $tbl = new oTableExt(array($projects->getFields(), $projects->getArray()));
        $tbl->setNamesColumns(array(
            'name'=>'Название',
            'site' => 'Сайт',
            'status' => 'Статус',
            'descr' => 'Описание',
            ));
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Projects/Info/?id=#id#" ajax="1">#name#</a>');
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Проекты');
    }
    function act_Info () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('project_info_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'project_info_html');
        }
        $id = (int)$this->request->get('id', 0);
        if (!$id) {
            return;
        }
        $project = new sep_Projects($id);
        $this->context->set('h1', $project->name);
        $this->context->set('site', $project->site);
        $this->context->set('descr', $project->descr);
        $this->context->set('date', $project->date_create);




        $sql = Stmt::prepare(se_Stmt::GET_SETS_PROJECT, array('project_id' => $id, Stmt::ORDER => 'name'));
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->viewColumns('name', 'search_type');
        
        $tbl->setViewIterator(true);
        $tbl->setNamesColumns(array(
            'name'=>'Сет',
            'type_search' => 'Типы'
        ));
        
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?set_id=#id#" ajax="1">#name#</a>');

        $sql = Stmt::prepare2(se_Stmt::GET_URLS_PROJECT, array('project_id' => $id));

        $tbl2 = new oTable(DBExt::selectToTable($sql));
        $tbl2->setViewIterator();
        $tbl2->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
        $this->context->set('tbl_pages', $tbl2);
        
    }

    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('edit_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'edit_html');
        }
        $this->context->set('h1', 'Редактирование проекта');
        $id = (int)$this->request->get('id', 0);
        if (!$id) {
            return;
        }
        $form = new oForm($this->fields, array('GET' => array('id' => $id)));
        $yaAccess = new sep_Projects();
        if ($yaAccess->getObject($id)) {
            if ($this->request->is('POST')) {
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $yaAccess->fillObjectFromArray($form->getValues());
                    $yaAccess->save();
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'SEParsing';
                    $iRoute->action = 'Projects';
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
                $yaAccess = new sep_Projects();
                $yaAccess->fillObjectFromArray($form->getValues());



                    $row = DBExt::getOneRow('sep_Sites', 'name', trim($this->request->get('site')));
                    if (isset($row)) {
                        $yaAccess->site_id = $row['id'];
                    } else {
                        $site = new sep_Sites();
                        $site->name = trim($this->request->get('site'));
                        $site->save();
                        $yaAccess->site_id = $row['id'] = $site->id;
                    }



                $yaAccess->save();
                $this->context->del('form');

                $iRoute = new InternalRoute();
                $iRoute->module = 'SEParsing';
                $iRoute->action = 'Projects';
                $actR = new ActionResolver();
                $act = $actR->getInternalAction($iRoute);
                $act->runAct();
            }
        }
        $this->context->set('h1', 'Добавление нового проекта');
    }

    function act_Del () {
        $yaAccess = new sep_Projects((int)$this->request->get('id'));
        $yaAccess->delete();
        $iRoute = new InternalRoute();
        $iRoute->module = 'SEParsing';
        $iRoute->action = 'Projects';
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

