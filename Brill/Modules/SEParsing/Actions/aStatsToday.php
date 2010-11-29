<?php

class aStatsToday extends Action{
    protected $defaultAct = 'view';
    protected function configure() {
        require_once $this->module->pathModels . 'sep_InterfaceCountCallToday.php';
    }

    function act_View () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('list_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'list_html');
        }

        $sql = Stmt::prepare(se_Stmt::STATS_CALLS_TODAY);
        $tbl = new oTable(DBExt::selectToTable($sql));
        $tbl->setNamesColumns(array(
            'interface'=>'Интерфейс',
            'host' => 'Источник',
            'count' => 'Выполнено запросов',
            'last_date' => 'Последнее действие',
            ));
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Статистика за сегодня');
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