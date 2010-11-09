<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author almaz
 */
class View {
    protected
        // фактически обозначает родительский шаблон по умолчанию. т.к. внутренние дожны задаваться в экшене
        $defaulTpl,
        $tpl,
        $aHeaders = array(),
        $httpStatus = '200',
        $context,
        $useParentTpl = true;

    public function  input ($context) { 
        if($context->is('error_page')) {
            $this->httpStatus = '404';
        }


       // $this->inputHeaders();

        $t = $this->context;
        RunTimer::addTimer('Viewing');
        RunTimer::addPoint('Viewing');
        include $this->context->getTopTpl();
        RunTimer::endPoint('Viewing');
         $r = RegistryRequest::instance();

         if ($r->isAjax()) {
             echo '<br />' . Log::viewLog();
         }
        //здесь уже можно делать вывод
        //$this->inputHeaders();
    }

    public function __construct(RegistryContext $context) {
        $this->context = $context;
    }
}

