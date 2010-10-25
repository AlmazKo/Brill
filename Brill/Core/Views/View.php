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
}

