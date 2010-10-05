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
    protected $defaultParent;
    protected $aHeaders = array();
    protected $httpStatus = '200';
     public function  input () {
         $context = RegistryContext::instance();
         if($context->is('error_page')) {
            $this->httpStatus = '404';
         }
         $this->inputHeaders();
         if ($context->get('parent_tpl')) {
             @include $this->dirTemplates . $defaultParent;
         }
        //здесь уже можно делать вывод
        //$this->inputHeaders();

    }
}

