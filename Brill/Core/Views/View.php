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
         $this->inputHeaders();
         if ($context->get('useParentTpl')) {
             @include $this->dirTemplates . $defaultParent;
         }
        //здесь уже можно делать вывод
        //$this->inputHeaders();
    }

    public function __construct($nameModule, RegistryContext $context) {
        $this->context = $context;
        if ($context->is('useParentTpl')) {
            $this->useParentTpl = $context->get('useParentTpl');
        } 
        if ($this->useParentTpl) {
            if ($context->is('parentTpl')) {
                $this->tpl = $this->get('parentTpl');
                $this->tpl = General::$loadedModules[$nameModule]->pathViews . $this->tpl;
            } else {
                $this->tpl = $this->defaultTpl;
            }
        } else  { echo '-----';Log::dump($context->getTpl('tpl'));
            if ($context->is('tpl')) {
                $this->tpl = $context->getTpl('tpl');

            } else {
                $this->tpl = $this->defaultTpl;
            }
        }

    }
}

