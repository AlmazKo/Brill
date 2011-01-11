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
        $errors = new mErrors();
        $tbl = new oTableExt(array($errors->getFields(), $errors->getArray()));
        $tbl->setNamesColumns(array('class'=>'Класс', 'descr'=> 'Сообщение'));
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Ошибки, возникшие при работе ботов');
    }

}