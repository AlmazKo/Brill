<?php
/**
 * Description of aSites
 *
 * @author almaz
 */
class aSites extends Action {
    protected $defaultAct = 'List';

    protected function configure() {
        require_once $this->module->pathModels . 'as_Sites.php';
        require_once $this->module->pathViews . 'vSubscribe.php';
        $this->context->set('title', 'Сайты');
        //$this->context->set('parentTpl', View::setTpl('sites_html.php'));

                        $route = new SimpleRouter();
        $route->module = 'Pages';
        $route->action = 'Pages';
        $route->act = 'view';
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($route);
        $act->execute(false);
    }

    /**setTpl
     * выводит список всех сайтов
     */
    public function act_List($context) {

        $route = new SimpleRouter();
        $route->module = 'Pages';
        $route->action = 'Pages';
        $route->act = 'view';
        $actR = new ActionResolver();
        $act = $actR->getInternalAction($route);
        $act->execute(false);


        $this->context->set('useParentTpl', !$this->request->isAjax());
        $this->context->setTpl('tpl', 'sites_html');
        $sites = new as_Sites();
        $tbl = new oTableExt(array($sites->getFields(), $sites->getArray()));
        $tbl->viewColumns('host', 'config_status');
        $tbl->setNamesColumns(array('name'=>'Хост',
                                    'config_status' => 'Статус'));
        $this->context->set('tbl', $tbl);
    }


   /**
     * выводит список всех сайтов
     */
    public function act_Add($context) {


        $this->context->set('useParentTpl', !$this->request->isAjax());
        $this->context->setTpl('tpl', 'site_add_html');
        $fields['host'] = array('title' => 'хост', 'value'=>'', 'type'=>'text', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $fields['rule'] = array('title' => 'Конфиг(XML)', 'value'=>'', 'type'=>'textarea', 'requried' => true, 'validator' => null, 'info'=>'', 'error' => false, 'attr' => '', $checked = array());
        $form = new oForm($fields);
        $this->context->set('form', $form);
        if ($this->request->is('POST')) {
            $form->fill($this->request->get('POST'));
            if ($form->isComplited()) {
                $site = new as_Sites();
                $site->host = $form->getFieldValue('host');
                $site->rule = $form->getFieldValue('rule');
                $site->save();
            }
        }
        $this->context->set('info_text', 'Создание нового сайта...');
    }


        /*
     * Отдаем родителю нашу вьюшку
     */
    protected function initView() {
        return new vSubscribe(RegistryContext::instance());
     }
}
