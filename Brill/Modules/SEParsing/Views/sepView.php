<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sepView
 *
 * @author almaz
 */
require_once CORE_PATH . 'Views/View.php';

class sepView extends View{
     protected $defaultParent = 'GPage_HTML.php';
     protected $dirTemplates;
     protected $aHeaders = array();


    // выводит хидеры
    function inputHeaders ($status = '200') {

    }

    public function __constructor() {
        //TODO пусть береть назване моделю из класса Route
        $this->dirTemplates = MODULES_PATH . 'SEParsing/Views/';
    }
}
