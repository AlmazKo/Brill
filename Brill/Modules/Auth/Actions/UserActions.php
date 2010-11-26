<?php
/*
 * Action Users
 *
 * @author Alexander
 */
class aUserActions extends Action{
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


}
