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
class Subscribe extends Action{
    protected $defaultAct = 'start';

    /**
     * Первый запуск рассылки
     */
    public function act_Start() {
        $rr = RegistryRequest::instance();
        $sites = new as_Sites;
        
      //  $sites->getArrayObjects('config_status', 'Yes');

     //   var_dump($sites->getFields());

        $context = RegistryContext::instance();
        if ($rr->isAjax()) {
             $context->set('useParentTpl', false);
        } else {
            $context->set('useParentTpl', true);
            $context->set('title', 'Рассылка');
        }
        $tbl = new oTableExt(array($sites->getFields(), $sites->getArrayObjects('config_status', 'Yes')));
        $f = new oFormFree();
        $tbl->viewColumns('host');
        $tbl->setNamesColumns(array(
            'host'=>'Выберите сайт'));
        $context->set('tbl', $tbl);
        $context->set('f', $f);
        $context->set('tpl', 'subscribe_start_html.php');
        $context->set('info_text_1', 'Общая форма для рассылки, некоторые данные могут быть избыточными');
        $userForm = new UserSubscribeForm();
        $idSubscribe = '0';
        $idUser = '0';
        if($rr->is('POST')) {
            $userForm->fill($rr->get('POST'));
            if ($userForm->isComplited()) {
               $file = $idUser . '_' . $idSubscribe . '.xml';
               $userForm->save($file);
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
               $this->act_Next();
            }
        }
       
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