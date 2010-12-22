<?php

class aErrors extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once CORE_PATH . 'Models/mErrors.php';
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('list_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'list_html');
        }
        $sql = Stmt::prepare2(st_Stmt::GET_ALL_ERRORS);
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->setNamesColumns(array('class'=>'Класс', 'descr'=> 'Сообщение'));
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Ошибки, возникшие при работе ботов');
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