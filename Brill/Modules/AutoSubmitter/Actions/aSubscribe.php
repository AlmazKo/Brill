<?php
/**
 * Subscribe
 *
 * Экшен занимающийся рассылкой
 *
 * @author almaz
 */
require_once AutoSubmitter::$pathModels .'as_Sites.php';
require_once AutoSubmitter::$pathViews .'vSubscribe.php';
require_once MODULES_PATH . AutoSubmitter::$name .'/UserSubscribeForm.php';
class aSubscribe extends Action{
    protected $defaultAct = 'start';

    protected function act_Add($context) {
        $fields['title'] = array('title' => 'Название', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $rr = RegistryRequest::instance();
        $form = new oForm($fields);
        $context->set('form', $form);
                $context->set('step', 0);
        if ($rr->is('POST')) {
            $form->fill($rr->get('POST'));
            if ($form->isComplited()) {
                return true;
            }
        }
        

        $context->set('info_text', '');
    }

    /**
     * ВЫБОР САЙтов для рассылки
     * @param <type> $context
     */
    protected function act_SelectSite($context) {
        $rr = RegistryRequest::instance();
        $sites = new as_Sites;
        $form = new oFormFree(array('GET' => array('step'=>'1'))); 
        $tbl = new oTableExt(array($sites->getFields(), $sites->getArrayObjects('config_status', 'Yes')));
       // $form = new UserSubscribeForm();
        if ($rr->is('POST')) {
            return true;
        } else {
            $tbl->viewColumns('host');
            $tbl->setNamesColumns(array(
                'host'=>'Выберите сайт'));
            $context->set('tbl', $tbl);
            $context->set('form', $form);
            $context->set('tpl', 'subscribe_start_html.php');
            $context->set('info_text', 'Выберите сайты');
            $context->set('step', 1);
        }
    }

    protected function act_FillForm($context) {
         $form = new UserSubscribeForm();
         $context->set('form', $form);
         $context->set('info_text', 'Внимательно заполните форму');
         $context->set('step', 2);
    }

    /**
     * Wizzard создания новой рассылки
     * @param RegistryContext $context
     */
    public function act_Start($context) {
                $rr = RegistryRequest::instance();
    //    if ($rr->isAjax()) {
            $context->set('useParentTpl', false);
        $context->set('tpl', 'subscribe_start_html.php');


        if ($rr->is('step')) {
            $step = (int) $rr->get('step');
        } else {
            $step = 0;
        }
        switch ($step) {
            case 0:
                 if (!$this->runAct('add')) {
                     break;
                 } 
            

            case 1:
                if (!$this->runAct('SelectSite')) {
                    break;
                }
            case 2:
                $this->runAct('FillForm');
        }

        
      //  $sites->getArrayObjects('config_status', 'Yes');

     //   var_dump($sites->getFields());


             $context->set('useParentTpl', false);

   //     }
//        $tbl = new oTableExt(array($sites->getFields(), $sites->getArrayObjects('config_status', 'Yes')));
//               $userForm = new UserSubscribeForm();
//        $idSubscribe = '0';
//        $idUser = '0';
//        if($rr->is('POST')) {
//            $userForm->fill($rr->get('POST'));
//            if ($userForm->isComplited()) {
//               $file = $idUser . '_' . $idSubscribe . '.xml';
//               $userForm->save($file);
               // $context->clean();
//               $s = new UserSubscribe($idUser, $file);
//               $sites = new as_Sites();
//               if ($user->groupIs(array('admin','user')))
//               $sites->getFree();
//               $s->id;

            /*
             * Узнаем права
             * Узнаем ид рассылки
             * Узнаем состояние расслыки
             * если новая
             * получаем список доступных сайтов
             * которые может юзать данная категория ( выбор всего 5 из доступных для триала)
             * выбираем сайты
             * запускаем
             *
             * запрашиваем стратегию
             * выводим ее результат
             *
             */
 //              $this->act_Next();
   //         }
 //       }
       
        //$context->set('form', $userForm);

    }

    /**
     * Работает нпосдредственно со стратегией и рассылкой
     */
     public function act_Next() {
        $rr = RegistryRequest::instance();
        /*
         * должен принимать только аякс запросы
         * возвращает html блок
         */
        if ($rr->isAjax()) {
            $context = RegistryContext::instance();
            $context->set('tpl', 'subscribe_next_html.php');
            $context->set('useParentTpl', false);
            $context->set('info_text_1', 'РАССЫЛКА');
            $context->set('form', new oForm());
            /*
             * Узнаем права
             * Узнаем ид рассылки
             * Узнаем состояние расслыки
             * если новая
             * получаем список доступных сайтов
             * которые может юзать данная категория ( выбор всего 5 из доступных для триала)
             * выбираем сайты
             * запускаем
             *
             * запрашиваем стратегию
             * выводим ее результат
             *
             */
        }
      //  self::run();
    }


    public static function run() {
        //Сделать запрос в queries
      //  $sites = as_Sites::getOneObject('config_status', 'Yes');

        $return = 'ok';//as_Strategy::run($sites[0]->siteHost);
        if ($return == 'ok') {
            // все супер, ставим отметку в базе (as_Subscribes) что прошли эту рассылку
            return true;
        }
        if (is_subclass_of($return, oForm)) {
            $context = RegistryContext::instance();
            //стратегия вернула форму значит еще чтото хочет
            $context->set('form', $return);
           return false;
        }
    }


    /*
     * Отдаем родителю нашу вьюшку
     */
    protected function initView() {
        return new vSubscribe(RegistryContext::instance());
     }
}