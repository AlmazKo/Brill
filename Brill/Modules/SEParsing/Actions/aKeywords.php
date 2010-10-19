<?php
/**
 * Description of Keywords
 *
 * @author almaz
 */
require_once MODULES_PATH . 'SEParsing/Views/vKeywords.php';

class aKeywords extends Action {
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
