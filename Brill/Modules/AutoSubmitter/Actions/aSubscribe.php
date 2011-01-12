<?php
/**
 * aSubscribe
 *
 * Экшен занимающийся рассылкой
 *
 * @author almaz
 */

class aSubscribe extends Action{
    protected $defaultAct = 'List';

    protected function configure() {
        require_once $this->module->pathModels . 'as_Sites.php';
        require_once $this->module->pathModels . 'as_Subscribes.php';
        require_once $this->module->pathModels . 'as_SubscribesSites.php';
        require_once $this->module->pathModels . 'as_SitesUsers.php';
        
        require_once $this->module->pathModule . 'UserSubscribeForm.php';
        require_once $this->module->pathDB . 'as_Stmt.php';
        require_once $this->module->pathLib . 'XmlParser.php';
        require_once $this->module->pathLib . 'as_Strategy.php';
        $this->context->setTopTpl('subscribe_start_html');
    }


    function act_Edit () {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('subscribes_edit');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'subscribes_edit');
        }
        
        $id = (int)$this->request->get('id', 0);
        if (!$id) {
            return;
        }

        $sql = Stmt::prepare2(as_Stmt::GET_SUBSCRIBE_USER, array('id'=>$id, 'user_id' => 0));
        $subscribes = new as_Subscribes();
        
        if ($subscribes->fillObjectFromSql($sql)) {
            if ($this->request->is('POST')) {
                $form = new UserSubscribeForm();
                $form->fill($this->request->get('POST'));
                if ($form->isComplited()) {
                    $subscribes->form = $form->getXmlAsText();
                    $subscribes->save();
                    $this->context->del('form');
                    $iRoute = new InternalRoute();
                    $iRoute->module = 'AutoSubmitter';
                    $iRoute->action = 'Subscribe';
                    $actR = new ActionResolver();
                    $act = $actR->getInternalAction($iRoute);
                    $act->runAct();
                }
            } else {
               
                $form = new oFormExt();
                $form->loadFromString($subscribes->form);
                $this->context->set('form', $form);
            }
             $this->context->set('h1', 'Редактирование рассылки "'. $subscribes->name.'"');
        } else {
             $this->context->set('h1', 'Редактирование рассылки');
        }
       
    }

    function act_Del () {
        $id = (int)$this->request->get('id', 0);
        
        $subscribe = DBExt::getOneRowSql(Stmt::prepare2(as_Stmt:: GET_SUBSCRIBE_USER, array('user_id' => 0, 'id' => $id)));
        if ($subscribe) {
            DB::query(Stmt::prepare2(as_Stmt::DEL_SUBSCRIBES_SITES_USER, array('user_id' => 0, 'subscribe_id' => $subscribe['id'])));
            DB::query(Stmt::prepare2(as_Stmt::DEL_SUBSCRIBE_USER, array('user_id' => 0, 'id' => $subscribe['id'])));
        }
        $iRoute = new InternalRoute();
        $iRoute->module = 'AutoSubmitter';
        $iRoute->action = 'Subscribe';
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($iRoute);
        $act->runAct();
    }

    /**
     * первый экшен в визарде.
     * добавление названия рассылки. возможно еще каких то параметров
     * @param RegistryContext $context
     * @return bool
     */
    private function _addStep1() {
        $this->context->set('info_text', '');
        $fields['name'] = array('title' => 'Название рассылки', 'value'=>'', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $form = new oForm($fields, array('step' => '0'));
        $this->context->set('form', $form);
        $this->context->set('step', 0);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $subscribe = new as_Subscribes();
                $subscribe->name = $form->getFieldValue('name');
                $this->session->set('newSubscribe', $subscribe);
                $this->request->clean();
                return true;
            }
        }
    }

    /**
     * второй экшен в визарде.
     * ВЫБОР САЙтов для рассылки
     * @param RegistryContext $context
     * @return bool
     */
    private function _addStep2() {
        $sites = new as_Sites;
        $form = new oFormExt(array(), array('step'=>'1'));
        $tbl = new oTableExt(array($sites->getFields(), $sites->getArray('config_status', 'Yes')));
        if ($this->request->is('POST')) {
            $post = $this->request->get('POST');
            if (isset($post['table_chk']) && is_array($post['table_chk'])) {
                $sites = array_keys($post['table_chk']);
                $this->session->set('newSelectedSites', $sites);
            }
            //TODO массив сайтов должен проверятся и сохрантся в сводную таблицу, после удачного всего прохождения
            return true;
        } else {
            $tbl->viewColumns('host');
            $tbl->setNamesColumns(array(
                'host'=>'Выберите сайт'));
            $this->context->set('tbl', $tbl);
            $this->context->set('form', $form);
            $this->context->set('tpl', 'subscribe_start_html.php');
            $this->context->set('info_text', 'Выберите сайты, на которых хотите разместить пресс-релиз');
            $this->context->set('step', 1);
        }
    }

    /**
     * третий экшен в визарде.
     * заполнение формы данных для рассылки
     * @param RegistryContext $context
     * @return bool
     */
    private function _addStep3() {
        $form = new UserSubscribeForm(array(), array('step'=>'2'));
        $this->context->set('form', $form);
        $this->context->set('info_text', 'Внимательно заполните форму');
        $this->context->set('step', 2);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $subscribe = $this->session->get('newSubscribe');
                $subscribe->form = $form->getXmlAsText();
                $subscribe->user_id = 0;
                $subscribe->date_created = time();
                $subscribe->save();

                $sites = $this->session->get('newSelectedSites');
                $subscribeSites = new as_SubscribesSites();
                $subscribeSites->subscribe_id = $subscribe->id;
                foreach ($sites as $siteId) {
                    $subscribeSites->site_id = $siteId;
                    $subscribeSites->save();
                }
                $linkNewSubscribe = Routing::constructUrl(array('act' => 'Run'), false) . '?id=' . $subscribe->id;
                $this->context->set('linkNewSubscribe', $linkNewSubscribe);
                return true;
            }
        }
    }

    /**
     * Wizzard создания новой рассылки
     * @param RegistryContext $context
     */
    public function act_Add() {
        $this->context->setTopTpl('subscribe_start_html');
        $step = $this->request->is('step') ? (int) $this->request->get('step') : 0;
        switch ($step) {
            /*
             * Очищаем пост при удачном выполнении,
             * чтобы эти данные не попали следующему экшену
             */
            case 0:
                 if ($this->_addStep1()) {
                     $this->request->clean();
                 } else {
                     break;
                 }
            case 1:
                if ($this->_addStep2()) {
                    $this->request->clean();
                } else {
                    break;
                }

            case 2:
                if ($this->_addStep3()) {
                    $this->request->clean();
                } else {
                    break;
                }
            case 3 :
                 $this->context->set('step', 3);
        }
    }

    /**
     * выводит список всех созданных рассылок
     */
    public function act_List() {

        if ($this->request->isAjax()) {
           $this->context->setTopTpl('subscribes_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'subscribes_html');
        }
        $subscribes = new as_Subscribes();

        $fields = $subscribes->getFields();
       //         Log::dump($fields);
        array_unshift($fields, "run");


        $sql = Stmt::prepare2(as_Stmt::GET_SUBSCRIBES_USER, array('user_id' => 0));
        $tbl = new oTableExt(DBExt::selectToTable($sql));
        $tbl->addCol('run');
        $tbl->viewColumns('run', 'name', 'form');
        $tbl->setNamesColumns(array('name'=>'Название',
                                    'run'=>'Запустить',
                                    'form' => 'Рассылка'));
        $tbl->setViewIterator(true);
        $tbl->setIsEdit(true);
        $tbl->setIsDel(true);
        $tbl->noSortColumns(array('run','date_begin'));
        $tbl->setCustomOpt('Run', 'Начать рассылку', 'start.png', 'Subscribe', 'Run', 'id');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);

        $sqlSt = Stmt::prepare2(as_Stmt::GET_SUBSCRIBES_STATUS_USER, array('user_id' => 0));
        $subscribesStatus = DBExt::selectToArray($sqlSt);
        $progress = array();
        foreach ($subscribesStatus as $value) {
            $sites = explode(',', $value['site_ids']);
            $statuses = explode(',', $value['status_ids']);
            $progress[$value['subscribe_id']]  = array_combine($sites, $statuses);

        }

        $this->context->set('progress', $progress);
    }


    /*
     * Запуск рассылки
     */
    public function act_Run() {
        if ($this->request->isAjax()) {
           $this->context->setTopTpl('run_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'run_html');
        }
        $form = null;
        $session = RegistrySession::instance();
        $this->context->set('h1','Рассылка');
        
        if ($session->is('as_ss_id')) {
            //какое идентификатор должен быть у формы
            $subscribeSiteIdName = $session->get('as_ss_id');
            $subscribeId = (int)$this->request->get($subscribeSiteIdName, 0);

            if ($this->request->getRequestPOST($subscribeSiteIdName)) {
         //       Log::dump($_POST);
                $result = DBExt::getOneRowSql(Stmt::prepare2(as_Stmt::GET_SITE_IN_USED_SUBSCRIBE, array('subscribe_id' => $subscribeId)));
                if ($result) {
                    $site = new as_Sites($result['site_id']);
                    $subscribe = new as_Subscribes($result['subscribe_id']);
                    $sitesUsers = new as_SitesUsers($result['site_id'], $this->userInfo['user']['id']);
                    
                    $this->context->set('h1','Рассылка "' . $subscribe->name . '"');
                    $strategy = new as_Strategy($site, $subscribe, $sitesUsers);
                    $result = $strategy->start(($this->request->get('POST')));
                    if($result instanceof oForm) {
                        $form = &$result;
                        $form->setHtmlBefore('<center>Форма для сайта '. $site->host. '</center>');
                        $subscribeSiteId = uniqid('as_ss_id');
                        $session->set('as_ss_id', $subscribeSiteId);
                        $form->setField($subscribeSiteId, array('type'=>'hidden', 'value' => $subscribeId));
                    } else if($result instanceof oError) {
                        $this->context->setError($result);
                    } else {
                        //форма успешно отправлена на сервер
                        $result = DBExt::getOneRowSql(Stmt::prepare2(as_Stmt::GET_SITE_IN_SUBSCRIBE, array('subscribe_id' => $subscribeId)));
                        if ($result) {
                            $linkNewSubscribe = Routing::constructUrl(array('act' => 'Run'), false) . '?id=' . $subscribe->id;
                            $this->context->set('text', '<a href="'.$linkNewSubscribe.'" ajax="1">Дальше</a>');
                        } else {
                            $this->context->set('text', 'Рассылка завершена успешно');
                        }
                        $this->context->setMessage('Форма успешно отправлена на сервер');
                        $this->request->clean();
                        return;
                    }
                } else {
                     $this->context->setError('Ошибка');
                }
            }
        }

        if (!$form && $this->request->getRequestGET('id')) {
            $subscribeId = (int)$this->request->get('id', 0);
            $result = DBExt::getOneRowSql(Stmt::prepare2(as_Stmt::GET_SITE_IN_SUBSCRIBE, array('subscribe_id' => $subscribeId)));
            if ($result) {
                $site = new as_Sites($result['site_id']);
                $subscribe = new as_Subscribes($result['subscribe_id']);
                $sitesUsers = new as_SitesUsers($result['site_id'], $this->userInfo['user']['id']);
                $this->context->set('h1','Рассылка "' . $subscribe->name . '"');
                $strategy = new as_Strategy($site, $subscribe, $sitesUsers);
                $result = $strategy->start();
                if($result instanceof oError) {
                    $this->context->setError($result->message);

                    $this->context->set('text', 'Повторить. Перейти к следущему сайту рассылки');
                } else {
                    $form = $strategy->getForm();
                    $form->setHtmlBefore('<center>Форма для сайта '. $site->host. '</center>');

                    //добавялем поле для идентификации конкретной рассылки
                    $subscribeSiteId = uniqid('as_ss_id');
                    $session->set('as_ss_id', $subscribeSiteId);
                    $form->setField($subscribeSiteId, array('type'=>'hidden', 'value' => $subscribeId));
                }

            } else {
               $this->context->setMessage('Не осталось не обработанных сайтов');
            }
        }
        $this->context->set('form', $form);
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