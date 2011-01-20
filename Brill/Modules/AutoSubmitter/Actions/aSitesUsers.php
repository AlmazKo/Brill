<?php
/**
 * Description of aSites
 *
 * @author almaz
 */
class aSitesUsers extends Action {
    protected $defaultAct = 'List';

    protected function configure() {
        require_once $this->module->pathModels . 'as_Sites.php';
        require_once $this->module->pathModels . 'as_SitesUsers.php';
        $authModule = General::$loadedModules['Auth'];
        require_once $authModule->pathModels . 'au_Users.php';
    }

    /**setTpl
     * выводит список всех сайтов
     */
    public function act_List() {
        if ($this->request->isAjax()) {
           $this->context->setTopTpl('html_list');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'html_list');
        }

        $sql = Stmt::prepare2(as_Stmt::GET_SITES_USERS_USER, array('user_id' => $this->userInfo['user']['id']));
        $tbl = new oTable(DBExt::selectToTable($sql));

        $tbl->setIsDel();
        $tbl->setIsEdit();
        $tbl->setNamesColumns(array('host'=>'Сайт'));
        $tbl->addRulesView('password', '******');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
        $this->context->set('h1', 'Мои сайты');
    }


   /**
     * выводит список всех сайтов
     */
    public function act_Add() {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('site_add_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'site_add_html');
        }
        $sqlSites = Stmt::prepare2(as_Stmt::ALL_SITES, array(), array (Stmt::ORDER => 'sort'));
        $listSites = new oList(DBExt::selectToList($sqlSites));
        $fields['site_id'] = array('title' => 'Сайт', 'value' => '', 'data' => $listSites, 'type'=>'select', 'required' => true, 'validator' => null, 'info'=>'Список поддерживаемых на данный момент сайтов', 'error' => false, 'attr' => '', $checked = array());
        $fields['login'] = array('title' => 'Логин', 'value'=>'', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['password'] = array('title' => 'Пароль', 'value'=>'', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $form = new oForm($fields);
        $this->context->set('form', $form);
        $this->context->set('info_text', 'Добавление настроек для нового сайта...');
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $siteUser = new as_SitesUsers();
                $siteUser->site_id = $form->getFieldValue('site_id');
                $siteUser->login = $form->getFieldValue('login');
                $siteUser->password = $form->getFieldValue('password');
                $siteUser->user_id = $this->userInfo['user']['id'];
                $siteUser->save();
                $this->context->del('form');
                $this->context->set('info_text', 'Настройки добавлены');
            }
        }
    }

    function act_Del () {
        $id = (int)$this->request->get('id', 0);
        $sqlSites = Stmt::prepare2(as_Stmt::DEL_SITE_USER, array('user_id' => $this->userInfo['user']['id'], 'site_id' => $id));
        DB::execute($prepare_stmt);
        $sql = Stmt::prepare(se_Stmt::IS_KEYWORDS_SET, array('set_id' => $id, Stmt::LIMIT => 1));
            $sitesUsers = new as_SitesUsers((int)$this->request->get('id'));
            $sets->delete();


        $iRoute = new InternalRoute();
        $iRoute->module = 'SEParsing';
        $iRoute->action = 'Sets';
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($iRoute);
        $act->runAct();
    }

    /**
     * Функция-обвертка, модули уровнем выще. для отображения
     * @param InternalRoute $iRoute
     */
    function _parent(InternalRoute $iRoute = null) {
        $this->context->set('title', 'Сайты');

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