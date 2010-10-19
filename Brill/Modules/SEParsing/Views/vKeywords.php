<?php
/**
 * Description of sepView
 *
 * @author almaz
 */

class vKeywords extends View{
     protected $defaultParent = 'GPage_HTML.php';
     protected $dirTemplates;
     protected $aHeaders = array();


    // выводит хидеры
    function inputHeaders ($status = '200') {

    }

    public function  __construct(RegistryContext $context) {
        $this->context = &$context;
        if (!$context->get('useParentTpl')) {
            $this->useParent = false;
            $this->defaultParent = $context->get('tpl');
        }
    }

    function  input() {
        $t = $this->context;
        include(SEParsing::$pathViews . $this->defaultParent);
    }
}
