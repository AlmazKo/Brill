<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Keywords
 *
 * @author almaz
 */
require_once MODULES_PATH . ParsingQuery::instance()->module . '/' . ParsingQuery::instance()->module . '.php';
require_once CORE_PATH . 'Actions/Action.php';
require_once MODULES_PATH . SEParsing::$name . '/Views/sepView.php';

class Keywords extends Action {
    protected $defaultAct = 'view';

    public function act_Add($context) {}

    public function act_View($context) {
       // $nav = $request->getNav();

       // обработка данных
        // выбор нужного вью
        // и получение небходимыъ данных для него (аторизация и прочее)

        $context->set('useParentTpl', true);

        $context->set('tpl', 'Keywords_HTML.php');

        $context->set('h1', __METHOD__);
        $context->set('title', 'Добавление ключевых слов');
    }



    /*
     * Отдаем родителю нашу вьюшку
     */
    protected function initView() {
        return new sepView();
     }

}
