<?php
/**
 * Subscribe
 *
 * Экшен занимающийся рассылкой
 *
 * @author almaz
 */

class aError extends Action {
    protected $defaultAct = 'vview';

    protected function configure() {}

    /**
     * Основная вьюшка
     */
    public function act_Vview() {
        if ($this->request->isAjax()) {
            $this->context->setTopTpl('block_error');
        } else {
            $this->context->setTopTpl('page_error');
        }
        $this->context->set('h1', 'Пользовательская ошибка');
       // $this->context->setHeader('menu', '404 Not allowed');
    }
}