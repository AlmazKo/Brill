<?php
/**
 * aSubscribe
 *
 * Экшен занимающийся рассылкой
 *
 * @author almaz
 */
require_once AutoSubmitter::$pathModels .'as_Sites.php';
require_once AutoSubmitter::$pathModels .'as_Subscribes.php';
require_once AutoSubmitter::$pathViews .'vSubscribe.php';
require_once MODULES_PATH . AutoSubmitter::$name .'/UserSubscribeForm.php';

class aSubscribe extends Action{
    protected $defaultAct = 'start';

    /**
     * первый экшен в визарде.
     * добавление названия рассылки. возможно еще каких то параметров
     * @param RegistryContext $context
     * @return bool
     */
    protected function act_Add($context) {
        $fields['name'] = array('title' => 'Название', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $rr = RegistryRequest::instance();
        $rs = RegistrySession::instance();
        $form = new oForm($fields);
        $context->set('form', $form);
        $context->set('step', 0);
        if ($rr->is('POST')) {
            $form->fill($rr->get('POST'));
            if ($form->isComplited()) {
                $subscribe = new as_Subscribes();
                $subscribe->name = $form->getFieldValue('name');
                $rs::set('newSubscribe', $subscribe);
                return true;
            }
        }
        $context->set('info_text', '');
    }

    /**
     * второй экшен в визарде.
     * ВЫБОР САЙтов для рассылки
     * @param RegistryContext $context
     * @return bool
     */
    protected function act_SelectSite($context) {
        $rr = RegistryRequest::instance();
        $rs = RegistrySession::instance();
        
        $sites = new as_Sites;
        $form = new oFormExt(array(), array('GET' => array('step'=>'1')));
        $tbl = new oTableExt(array($sites->getFields(), $sites->getArrayObjects('config_status', 'Yes')));
        if ($rr->is('POST')) {
            $rs->set('newSelectedSites', $rr->get('POST', 'table_chk'));
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

    /**
     * третий экшен в визарде.
     * заполнение формы данных для рассылки
     * @param RegistryContext $context
     * @return bool
     */
    protected function act_FillForm($context) {
        $rr = RegistryRequest::instance();
        $rs = RegistrySession::instance();
        $form = new UserSubscribeForm(array(), array('GET' => array('step'=>'2')));
        $context->set('form', $form);
        $context->set('info_text', 'Внимательно заполните форму');
        $context->set('step', 2);
        if ($rr->is('POST')) {
            $form->fill($rr->get('POST'));
            if ($form->isComplited()) {
                $subscribe = $rs::get('newSubscribe');
                $subscribe->form = $form->getXmlAsText();
                $subscribe->user_id = 0;
                $subscribe->date_created = time();
                $subscribe->add();
                return true;
            }
        }
    }

    /**
     * Wizzard создания новой рассылки
     * @param RegistryContext $context
     */
    public function act_Start($context) {
        $rr = RegistryRequest::instance();
        //todo сделать только для if ($rr->isAjax()) {
        $context->set('useParentTpl', false);
        $context->set('tpl', 'subscribe_start_html.php');
        
        $step = $rr->is('step') ? (int) $rr->get('step') : 0;

        switch ($step) {
            case 0:
                 if ($this->runAct('add')) {
                     /*
                      * Очищаем пост при удачном выполнении,
                      * чтобы эти данные не попали следующему экшену
                      */
                     $rr->clean();
                 } else {
                     break;
                 }
            case 1:
                if ($this->runAct('SelectSite')) {
                    $rr->clean();
                } else {
                    break;
                }
            case 2:
                if ($this->runAct('FillForm')) {
                    $rr->clean();
                } else {
                    break;
                }
            case 3 :
                 $context->set('step', 3);
        }
        $context->set('useParentTpl', false);

        #возможный вариант
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

    /**
     * выводит список всех созданных рассылок
     */
    public function act_List($context) {
        $context->set('useParentTpl', false);
        $context->set('tpl', 'subscribes_html.php');

        $subscribes = new as_Subscribes();
        $tbl = new oTableExt(array($subscribes->getFields(), $subscribes->getArrayObjects()));
        $tbl->viewColumns('name', 'date_begin');
        $tbl->setNamesColumns(array('name'=>'Название',
                                    'date_begin' => 'Статус'));
        $context->set('tbl', $tbl);
    }

   //todo реализивать общение со стратегией
    public static function run() {
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