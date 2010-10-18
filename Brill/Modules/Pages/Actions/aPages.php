<?php
/**
 * Subscribe
 *
 * Экшен занимающийся рассылкой
 *
 * @author almaz
 */
require_once Pages::$pathModels .'mPages.php';
require_once Pages::$pathViews .'vPages.php';
//require_once MODULES_PATH . AutoSubmitter::$name .'/as_Strategy.php';
class aPages extends Action {
    protected $defaultAct = 'view';

    /**
     * Основаня вьюшка
     */
    public function act_View() {
        $context = RegistryContext::instance();
        $context->set('useParentTpl', true);
        $context->set('tpl', 'subscribe_start_html.php');
        $context->set('title', 'Рассылка');
        $page = new mPages();
        if (General::$route->nav && isset(General::$route->nav['id'])) {
            $idPage = (int)General::$route->nav['id'];
        } else {
            $idPage = 0;
        }
        $page->getPkObject($idPage);
        $context->set('content', $page->content);
        $fields['login'] = array('title' => 'Логин:', 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['password'] = array('title' => 'Пароль:', 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $auth = new oForm($fields);
        $context->set('auth', $auth);
        //$return = f::run($sites[0]->siteHost);

    }





    /*
     * Отдаем родителю нашу вьюшку
     */
    protected function initView() {
        return new vPages(RegistryContext::instance());
     }
}
