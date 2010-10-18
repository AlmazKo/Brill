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
        $sites = $sites->getArrayObjects('config_status', 'Yes');
        $context = RegistryContext::instance();
        if ($rr->isAjax()) {
             $context->set('useParentTpl', false);
        } else {
             $context->set('useParentTpl', true);
            $context->set('title', 'Рассылка');
        }
       $context->set('tpl', 'subscribe_start_html.php');
       $context->set('info_text_1', 'Текст текст текст текст текст текст текст текст текст текст текст текст текст ');
       $userForm = new UserSubscribeForm();
        

        if($rr->is('POST')) {
            $userForm->fill($rr->get('POST'));
            if ($userForm->isComplited()) {
               $userForm->save();
            }
        }
        $context->set('form', $userForm);


        //Log::dump($sxe);
        //$return = f::run($sites[0]->siteHost);
    }
    public function act_Next() {
        /*
         * должен принимать только аякс запросы
         * возвращает html блок
         */

        $context = RegistryContext::instance();
        $context->set('tpl', 'subscribe_next_html.php');
        $context->set('useParentTpl', false);
        $context->set('info_text_1', 'Текст для подгружаемого аяксом конетента');
        $context->set('form', new oForm());

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
