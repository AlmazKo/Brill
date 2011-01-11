<?php

class aLimits extends Action {
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_LimitsIpForHosts.php';
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('list_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'list_html');
        }

        $sql = Stmt::prepare(se_Stmt::LIMITS_HOSTS);
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->setNamesColumns(array(
            'name'=>'Источник',
            'every_day' => 'В день',
            'every_hour' => 'В час',
            'every_min' => 'В минуту',
            ));
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Ограничения');
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