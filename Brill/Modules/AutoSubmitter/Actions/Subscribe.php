<?php
/* 
 * 
 */

/**
 * Description of Subscribe
 *
 * @author almaz
 */
class Subscribe extends Action{
    protected $defaultAct = 'start';
    //put your code here
    private function  __construct(UserForm $form, User $user) {
        
    }
    private function act_Start() {
        $sites = AS_site::getRequiredSite();
        as_Strategy::run($sites[0]->siteHost);
        $context->set('useParentTpl', true);
        $context->set('messNeedAddData', 'Для авторизации на сайте требуется ввести дополнительные данные');
        $context->set('messNeedAddData', 'Для авторизации на сайте требуется ввести дополнительные данные');

    }
    private function act_Next() {

    }


    public static function run() {
        
    }
}
