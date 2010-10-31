<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of vSubscribe
 *
 * @author almaz
 */

class vSubscribe extends View {
     protected $defaultParentTpl = 'subscribe_parent_html.php';
     protected $defaultTpl = false;
     protected $dirTemplates;

    // выводит хидеры
    function inputHeaders ($status = '200') {

    }

    public function  __construct(RegistryContext $context) {
        $this->defaultParentTpl = General::$loadedModules['AutoSubmitter']->pathViews . 'subscribe_start_html.php';
        parent::__construct('AutoSubmitter', $context);
    }

    function  input() {
        $t = $this->context;
        include($this->parentTpl);
    }
}

