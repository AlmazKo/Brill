<?php
/**
 * Родительский класс для всех экшенов
 *
 * @author Almazko
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
    protected $_user = null;
    public $session;
    public $context;
    public $view;

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
        if (method_exists ($this, $parentAct)) {
            return $this->$parentAct();
        } else {
            Log::warning('В "' . __CLASS__ . '" не реализован родительский метод: '.$parentAct);
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
        $route = Routing::instance();
        $route->set('act', ucfirst($nameAct));
        
        $act = 'act_' . ucfirst($nameAct);
        if (method_exists ($this, $act)) {
           General::runEvent(GENERAL::EVENT_BEFORE_RUNACT);
           return $this->$act();
        } else {
            Log::warning('В "' . __CLASS__ . '" не реализован метод: ' . $act);
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

    /**
     * Вызвать другой экшен, как родительский
     * @param InternalRoute $iRoute
     */
    protected function _parent(InternalRoute $iRoute = null) {
        if (!$iRoute) {
            $iRoute = new InternalRoute();
            $iRoute->module = General::$defaultModule;
        }
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($iRoute);
        $act->runParentAct();
    }
}