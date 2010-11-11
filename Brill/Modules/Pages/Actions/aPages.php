<?php
/**
 * Subscribe
 *
 * Экшен занимающийся рассылкой
 *
 * @author almaz
 */

class aPages extends Action {
    protected $defaultAct = 'view';

    protected function configure() {
        require_once $this->module->pathModels .'mPages.php';
      #  require_once $this->module->pathViews .'vPages.php';
        $this->_parent();
    }
    
    /**
     * Основаная вьюшка
     */
    public function act_View() {

        $this->context->set('title', 'Рассылка');

        $page = new mPages();
        if ($this->route->nav && isset($this->route->nav['id'])) {
            $idPage = (int)General::$route->nav['id'];
        } else {
            $idPage = 0;
        }
        $page->getObject($idPage);

        if ($this->isInternal) {
            //пока костыль. чтобы этот экшен могли юзать другие
            return true;
        }
        //$return = f::run($sites[0]->siteHost);

        $menu[0]['node'] = 'Мои рассылки';
        $menu[0][0] = 'Завершившиеся';
        $menu[0][1] = 'В процессе';
        $menu[1] = 'Новая рассылка';

    //    Log::dump($menu);
        $this->context->set('menu', $menu);
        $this->context->set('content', $page->content);
    }



    function _parent(InternalRoute $iRoute = null) {
        $this->context->setTopTpl('pages_parent_html', 'Pages');
        $this->context->setTpl('content', 'pages_content_html', 'Pages');

        $fields['login'] = array('title' => 'Логин', 'requried' => true, 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $fields['password'] = array('title' => 'Пароль', 'requried' => true, 'value'=>'', 'type'=>'text', 'validator' => null, 'info'=>'', 'error' => false, $checked = array());
        $auth = new oForm($fields);
        $this->context->set('auth', $auth);

    }

}