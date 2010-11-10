<?php

class aThematics extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_Thematics.php';
        $this->context->set('title', 'Тематики');
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
        $tbl->addRulesView('name', '<a href="' . WEB_PREFIX . 'SEParsing/Keywords/?thematic_id=#id#">#name#</a>');

        $this->context->set('h1', 'Все тематики');
        $this->context->set('tbl', $tbl);

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

