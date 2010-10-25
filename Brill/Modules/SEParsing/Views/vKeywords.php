<?php
/**
 * Description of sepView
 *
 * @author almaz
 */
               
class vKeywords extends View{
     protected $defaultParentTpl = 'parent.php';
     protected $defaultTpl = false;
     protected $dirTemplates;

    // выводит хидеры
    function inputHeaders ($status = '200') {

    }

    public function  __construct(RegistryContext $context) {
        var_dump(Pages);
        die();
        $this->defaultParentTpl = Pages::$pathViews . 'pages_parent_html.php';
        var_dump($this->defaultParentTpl);
        $this->context = $context;
        if ($context->is('useParentTpl')) {
            if ($context->is('parentTpl')) {
                $this->parentTpl = $this->get('parentTpl');
            } else {
               $this->parentTpl = SEParsing::$pathViews . $this->defaultParentTpl;
            }
        } else  {
            if ($context->is('tpl')) {
                $this->parentTpl = $this->get('tpl');
            } else {
               $this->parentTpl = SEParsing::$pathViews . $this->defaultTpl;
            }
        }
    }

    function  input() {
        $t = $this->context;
        include($this->defaultParentTpl);
    }
}
