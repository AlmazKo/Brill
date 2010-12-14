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
        $authModule = General::$loadedModules['Auth'];
        require_once $this->module->pathModels .'mPages.php';
        require_once $authModule->pathModels . 'au_Users.php';
        require_once $authModule->pathModels . 'au_Groups.php';
        $this->_parent();
    }
    
    /**
     * Основаная вьюшка
     */
    public function act_View() {
        $urlLogout = Routing::constructUrl(array('module' => 'Auth','action' => 'Auth', 'act' => 'logout'), false);
        $user = $this->session->get('user');
        $group = $this->session->get('userGroup');
        $this->context->set('user', $user);
        $this->context->set('userGroup', $group);
        $this->context->set('urlLogout', $urlLogout);

        $this->context->set('title', '');

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
    }

}