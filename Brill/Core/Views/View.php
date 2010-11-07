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
abstract class View {
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
        include $this->context->getTopTpl();
         
        //здесь уже можно делать вывод
        //$this->inputHeaders();
    }

    public function __construct($nameModule, RegistryContext $context) {
        $this->context = $context;
    }
}

