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
        $this->defaultParentTpl = AutoSubmitter::$pathViews . 'subscribe_start_html.php';
        $this->context = $context;
        if ($context->is('useParentTpl') && $context->get('useParentTpl')) {
            if ($context->is('parentTpl')) {
                $this->parentTpl = $this->get('parentTpl');
            } else {
                $this->parentTpl = SEParsing::$pathViews . $this->defaultParentTpl;
            }
        } else  {
            if ($context->is('tpl')) {
                $this->parentTpl = $context->get('tpl');
            } else {
               $this->parentTpl = SEParsing::$pathViews . $this->defaultTpl;
            }
        }
        
    }

    function  input() {
        $t = $this->context;
        include($this->parentTpl);
    }
}

