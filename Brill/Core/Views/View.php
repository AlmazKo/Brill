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
    protected $parentTpl;
    protected $tpl;
    protected $aHeaders = array();
    protected $httpStatus = '200';
    protected $context;
    
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
        if ($context->is('useParentTpl')  && $context->get('useParentTpl')) {
            if ($context->is('parentTpl')) {
                $this->parentTpl = $this->get('parentTpl');
                
            } else {
               $this->parentTpl = $this->defaultParentTpl;
            }
            $context->set('tpl', General::$loadedModules[$nameModule]->pathViews . $context->get('tpl'));
        } else  {
            if ($context->is('tpl')) {
                $this->parentTpl = $context->get('tpl');
                $context->set('tpl', General::$loadedModules[$nameModule]->pathViews . $context->get('tpl'));
            } else {
               $this->parentTpl = General::$loadedModules[$nameModule]->pathViews . $this->defaultTpl;
            }
        }
    }
}

