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

        $sql = Stmt::prepare2(au_Stmt::GET_SITES_USERS_USER, array ('user_id' => $this->userInfo['user']['id']));
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

        $fields['host'] = array('title' => 'хост', 'value'=>'', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['rule'] = array('title' => 'Конфиг(XML)', 'value'=>'', 'type'=>'textarea', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $form = new oForm($fields);
        $this->context->set('form', $form);
        $this->context->set('info_text', 'Создание нового сайта...');
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $site = new as_Sites();
                $site->host = $form->getFieldValue('host');
                $site->rule = $form->getFieldValue('rule');
                $site->save();
                $this->context->del('form');
                $this->context->set('info_text', 'Сайт успешно добавлен');
            }
        }
        
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