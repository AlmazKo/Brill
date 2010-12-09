<?php
/*
 * Action Users
 *
 * @author Alexander
 */
class aUser extends Action{
   protected $defaultAct = 'LogIn';

    protected function configure() {
        require_once $this->module->pathModels . 'sep_Keywords.php';
        require_once $this->module->pathModels . 'sep_Thematics.php';
        require_once $this->module->pathModels . 'sep_Sets.php';
        require_once $this->module->pathModels . 'sep_Regions.php';
        require_once $this->module->pathModels . 'sep_UrlKeywords.php';
        require_once MODULES_PATH . 'SEParsing/DB/Stmt.php';
    }

    /**
     * страничка регистрации
     * выводит форму
     * и ожидает пост
     * делает запись в базу при успешной заполнении формы
     */
    public function act_Registration() {

    }
    /**
     * Осуществляем вход
     * выводит форму для автооризации
     * ожидает данные из поста
     * при успехе сохраняет сессию
     */
    public function act_LogIn() {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('auth_login');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'auth_login');
        }
        $fields = array();
        $fields['login'] = array('title' => 'Логин', 'required' => true, 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['password'] = array('title' => 'Пароль', 'required' => true, 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $form = new oForm($fields);
        $this->context->set('form', $form);
        $this->context->get('title', 'Автороизация');
    }

    /**
     * страница проверки хеша
     * ожидает получить логин и хэш
     */
    public function act_CheckEmail() {

    }

    /**
     * Осуществляет выход
     * сбрасыватся все данные сессии
     *
     */
    public function act_LogOut() {

    }

    /*
     * Страничка смены пароля
     */
    public function act_FogottonPass() {

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