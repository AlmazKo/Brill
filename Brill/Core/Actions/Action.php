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
    //ссылка на родительскую конфигурацию
    public $module;
    protected $defaultAct;
    protected $request;
    protected $act = null;
    //внутренний вызов экшена?
    protected $isInternal;
    public $search = null;
    public $route;

    /*
     * Запускаем экт и выводим вьюшку
     * @param bool $view - выводить ли вью
     */
    public function execute ($view = true) {
        if($this->act) {
            $this->runAct($this->act);
        } else {
            $this->runAct($this->defaultAct);
        }

        if ($view) {
            $this->view = $this->initView();
            $this->input();
        }
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
    abstract protected function configure();

    public function  __construct($module, $act, $isInternal = false) {
        $this->module = $module;
        $this->act = $act;
        $this->isInternal = $isInternal;
        $this->request = RegistryRequest::instance();
        $this->context = RegistryContext::instance();
        $this->session = RegistrySession::instance();
        $this->configure();
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


    function changeSorting($field, $sort) {

    }
}