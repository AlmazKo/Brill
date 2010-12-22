<?php

class aRunnerDaemons extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'st_Hosts.php';
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('list_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'list_html');
        }
        $sql = Stmt::prepare2(st_Stmt::GET_ALL_RUNNER_DAEMONS, array(''));
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Демоны');
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