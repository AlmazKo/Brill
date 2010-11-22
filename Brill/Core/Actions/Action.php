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
include_once 'ActionWrapContext.php';

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
        if ($this->act) {
            $this->runAct($this->act);
        } else {
            $this->runAct($this->defaultAct);
        }

        if ($view) {
            $this->view = $this->initView();
            $this->input();
        }
    }

    public function runParentAct() {

        $parentAct = '_parent';
      //  Log::dump($parentAct);
        if (method_exists ($this, $parentAct)) {
            return $this->$parentAct();
        } else {
            Log::warning('В экшене  "' . __CLASS__ . '" не реализован метод: '.$parentAct);
            return false;
        }
    }

    /**
     * Запустить акт
     * @param string $nameAct Имя акта
     * @param array $settings Массив, мозможных настроек, если вызывается другим актом
     */
    final function runAct($nameAct =  null, $settings = array()) {
        if (!$nameAct) {
            $nameAct = $this->defaultAct;
        }
        $act = 'act_' . ucfirst($nameAct);
        if (method_exists ($this, $act)) {
            return $this->$act();
        } else {
            Log::warning('В экшене  "' . __CLASS__ . '" не реализован метод: ' . $act);
            return false;
        }

    }
    /**
     * Конфигурация Экшена
     */
    abstract protected function configure();

    final public function  __construct($module, $act, $isInternal = false) {

        $this->module = $module;
        $this->act = $act;
        $this->isInternal = $isInternal;
        $this->request = RegistryRequest::instance();
        $this->context = RegistryContext::instance();

        $this->configure();
        $this->session = RegistrySession::instance();

        //срабатывание эвента EVENT_INIT_ACTION
        if (!$isInternal) {
            General::runEvent(GENERAL::EVENT_AFTER_CONSTRUCT_ACTION);
        }
        
    }

    public function input() {
        $this->view->input(RegistryContext::instance());
    }

    /*
     * factory method
     */
    protected function initView() {
        return new View($this->context);
    }


    function changeSorting($field, $sort) {

    }


}