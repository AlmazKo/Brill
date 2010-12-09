<?php
/**
 * aSubscribe
 *
 * Экшен занимающийся рассылкой
 *
 * @author almaz
 */

class aSubscribe extends Action{
    protected $defaultAct = 'start';

    protected function configure() {
        require_once $this->module->pathModels . 'as_Sites.php';
        require_once $this->module->pathModels . 'as_Subscribes.php';
        require_once $this->module->pathModels . 'as_SubscribesSites.php';

        require_once $this->module->pathViews . 'vSubscribe.php';
        require_once $this->module->pathModule . 'UserSubscribeForm.php';
        require_once $this->module->pathDB . 'as_Stmt.php';
        require_once $this->module->pathLib . 'XmlParser.php';
        require_once $this->module->pathLib . 'as_Strategy.php';
        $this->context->setTopTpl('subscribe_start_html');
    }
    /**
     * первый экшен в визарде.
     * добавление названия рассылки. возможно еще каких то параметров
     * @param RegistryContext $context
     * @return bool
     */
    protected function act_Add() {
        $fields['name'] = array('title' => 'Название', 'value'=>'', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $form = new oForm($fields);
        $this->context->set('form', $form);
        $this->context->set('step', 0);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $subscribe = new as_Subscribes();
                $subscribe->name = $form->getFieldValue('name');
                $this->session->set('newSubscribe', $subscribe);
                return true;
            }
        }
        $this->context->set('info_text', '');
    }

    /**
     * второй экшен в визарде.
     * ВЫБОР САЙтов для рассылки
     * @param RegistryContext $context
     * @return bool
     */
    protected function act_SelectSite() {
        $sites = new as_Sites;
        $form = new oFormExt(array(), array('GET' => array('step'=>'1')));
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
            $this->context->set('info_text', 'Выберите сайты');
            $this->context->set('step', 1);
        }
    }

    /**
     * третий экшен в визарде.
     * заполнение формы данных для рассылки
     * @param RegistryContext $context
     * @return bool
     */
    protected function act_FillForm() {
        $form = new UserSubscribeForm(array(), array('GET' => array('step'=>'2')));
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
                return true;
            }
        }
    }

    /**
     * Wizzard создания новой рассылки
     * @param RegistryContext $context
     */
    public function act_Start() {

        //TODO вынести в отдельный файл


//        require_once $this->module->pathLib . 'AS_xmlMapper.php';
//        require_once $this->module->pathModule . 'UserData.php';
//        require_once $this->module->pathModule . 'UserDataProject.php';
//        require_once $this->module->pathModule . 'Strategy.php';
  //      require_once $this->module->pathModule . 'AS_Bot.php';
//        if ($this->request->isAjax()) {
//           $this->context->setTopTpl('subscribe_start_html');
//        } else {
//            $this->_parent();
//            $this->context->setTpl('content', 'subscribe_start_html');
//        }

        $this->context->setTopTpl('subscribe_start_html');
        $step = $this->request->is('step') ? (int) $this->request->get('step') : 0;

        switch ($step) {
            case 0:
                 if ($this->runAct('add')) {
                     /*
                      * Очищаем пост при удачном выполнении,
                      * чтобы эти данные не попали следующему экшену
                      */
                     $this->request->clean();
                 } else {
                     break;
                 }
            case 1:
                if ($this->runAct('SelectSite')) {
                    $this->request->clean();
                } else {
                    break;
                }
            case 2:
                if ($this->runAct('FillForm')) {
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
        $tbl = new oTableExt(array($subscribes->getFields(), $subscribes->getArray()));
        $tbl->viewColumns('name', 'date_begin');
        $tbl->setNamesColumns(array('name'=>'Название',
                                    'date_begin' => 'Статус'));

        $tbl->setCustomOpt('Run', 'Начать рассылку', 'start.png', 'Subscribe', 'Run', 'id');
        $tbl->sort(Navigation::get('field'), Navigation::get('order'));
        $this->context->set('tbl', $tbl);
    }


//    public static function run() {
//        $return = 'ok';//as_Strategy::run($sites[0]->siteHost);
//        if ($return == 'ok') {
//            // все супер, ставим отметку в базе (as_Subscribes) что прошли эту рассылку
//            return true;
//        }
//        if (is_subclass_of($return, oForm)) {
//            $context = RegistryContext::instance();
//            //стратегия вернула форму значит еще чтото хочет
//            $context->set('form', $return);
//           return false;
//        }
//    }


    /*
     * Твой экшен
     * поправил только подключение файлов
     * метод должен запускать с запроса /ba/AutoSubmitter/Subscribe/Run/
     * Обзови католог на хосте 'ba' для большей совместимости и подключи хтаксес
     *
     * Сделал тебе шаблон, его так включать здесь:
     *  $this->context->set('useParentTpl', false);
     *  $this->context->set('tpl', 'subscribe_run_html.php');
     *
     * все делай через аякс, по аналогии с другими методами
     *
     * все пути и другие статичные вещи сохрани или в константы или в свойства какого нить класса
     *
     * приведи все к зендовским стандартам. тяжело читать
     */



    public function act_Run() {
        if ($this->request->isAjax()) {
           $this->context->setTopTpl('run_html');
        } else {
            $this->_parent();
            $this->context->setTpl('content', 'run_html');
        }
        $subscribeId = (int)$this->request->get('id', 0);
        $form = null;
        if ($this->request->getRequestPOST('deamonic_id')) {
            $subscribeId = (int)$this->request->get('deamonic_id', 0);
           // Log::dump($this->request->get('POST'));
            $result = DBExt::getOneRowSql(Stmt::prepare2(as_Stmt::GET_SITE_IN_USED_SUBSCRIBE, array('subscribe_id' => $subscribeId)));
            if ($result) {
                $site = new as_Sites($result['site_id']);
                $subscribe = new as_Subscribes($result['subscribe_id']);
                $strategy = new as_Strategy($site, $subscribe);
                $result = $strategy->start(($this->request->get('POST')));
                if($result instanceof oForm) {
                    $form = &$result;
                    $form->setHtmlBefore('Форма для сайта '. $site->host);
                    $form->setField('deamonic_id', array('type'=>'hidden', 'value' => $subscribeId));

                }
            } else {
                 $this->context->setError('Ошибка');
            }
            //запрашиваем сайт через busy
            //отправляем фору серверу
            //если все ок. т.е. нашли в респонесе
            //ставим отметку что покончили с сайтом
        }

        if (!$form && $this->request->getRequestGET('id')) {
            $result = DBExt::getOneRowSql(Stmt::prepare2(as_Stmt::GET_SITE_IN_SUBSCRIBE, array('subscribe_id' => $subscribeId)));
            if ($result) {
                $site = new as_Sites($result['site_id']);
                $subscribe = new as_Subscribes($result['subscribe_id']);
                $strategy = new as_Strategy($site, $subscribe);
                $strategy->start();
                $form = $strategy->getForm();

                $form->setHtmlBefore('Форма для сайта '. $site->host);
                $form->setField('deamonic_id', array('type'=>'hidden', 'value' => $subscribeId));
            } else {
               //все закончилось - ставим отметку в субскрибе и пишем об этом пользователю
            }
        }
        $this->context->set('form', $form);
               
        /*
         * проверка что рассылка текущего юзера
         * тогда добавляем в список подохрительных
         */


//        $site = new as_Sites();
//        $site->getObject($this->request->get('id'));
//        $strategy = new Strategy($site, $user);
//        $strategy->run();
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//        include_once $this->module->pathModule. 'AS_site.php';
//        include_once $this->module->pathModule. 'UserData.php';
//        include_once $this->module->pathModule. 'UserDataProject.php';
//        include_once $this->module->pathModule. 'AS_xmlMapper.php';
//        include_once $this->module->pathModule. 'AS_Bot.php';
//        include_once $this->module->pathModule. 'Strategy.php';
//        include_once $this->module->pathModule. 'View.php';
//
//
//
//        if ($isForm){
//            // input view
//        }
//        $obj_AS_Site = new AS_site();
//
//        $obj_UserDataProject = new UserDataProject();
//
//        $obj_Strategy = new Strategy($obj_UserDataProject, $obj_AS_Site);
//
//        $obj_View = new View();
//
//        $k = 0;
//
////        $this->user_send = $_POST;//для заполнения что пользователь прислал, напишем. Этот кал нужно убрать потом
//
////        $obj_UserDataProject->setData($this->user_send);
//
//        while (($obj_Strategy->end == 'NO')||(empty($obj_Strategy->end))){
//            //если мы имеем что-то присланное от пользователя, то смотрим что имеем, пишем
///*
//            if ($this->user_send){
//                $this->data = $this->user_send;
//            }
// *
// */
//            //нам нужно общаться со стратегией сообщая заполненные пользователем нулевые поля
//            $this->data = $obj_Strategy->work($this->data);
//            //если у нас есть что-то для отображения, то отображаем, если нет, то пусть дальше работает
//            if ($this->data){
//                $obj_View->stek .= $this->data;
//                //$obj_View->Print_RData($this->data);
//                //$obj_View->UserFormProject();
//                //die();
//                //echo $this->data . "<br />";
//            };
//            if ($obj_Strategy->end == 'NO'){
//                //если правило которое мы выполняли - с ошибкой,то нужно предложить пользователю попробывать снова
//                //или отказаться
//                //die('pravilo vipolneno s oshibkoi');
//                $obj_View->FormRepeat();
//                break;
//            }
//
//            if ($k == 10){
//                die('diiiiieeeee LIMIT 10');
//            };
//            $k++;
//        }
//        $obj_View->PrintData();

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