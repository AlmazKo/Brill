<?php

class aSets extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_Sets.php';
        
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

