<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Action
 *
 * @author Alexander
 */
abstract class Action {
    protected $defaultAct;
    protected $request;
    protected $act = null;
    protected $nav = null;
    protected $search = null;


    public function execute () {
        if($this->act) {
            $this->runAct($this->act);
        } else {
            $this->runAct($this->defaultAct);
        }
        $this->view = $this->initView();
        $this->input();
    }

    /**
     * Запустить акт
     * @param string $nameAct Имя акта
     * @param array $settings Массив, мозможных настроек, если вызывается другим актом
     */
    final function runAct($nameAct =  null, $settings = array()) {
        if (empty($nameAct)) {
            $nameAct = $this->defaultAct;
        }
        $nameAct = ucfirst($nameAct);
        $act = 'act_' . ucfirst($nameAct);
        if (method_exists ($this, $act)) {
            return $this->$act(RegistryContext::instance());
        } else {
            Log::warning('In action  "' . __CLASS__ . '" not implement Act: ' . $nameAct);
            return false;
        }

    }

    public function  __construct($act = '', $queryString = null, $nav = null, $search = null) {
        $this->act = $act;
        $this->nav = $nav;
        $this->request = RegistryRequest::instance();

    }

    public function input() {
        $this->view->input(RegistryContext::instance());
    }

    /*
     * factory method
     */
    protected function initView() {
       Log::warning('Не определен метод: ' . get_class($this) .'->initView()');
    }
}