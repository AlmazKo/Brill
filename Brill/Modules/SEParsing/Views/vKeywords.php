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
        // используем шаблон из модуля Pages
        $this->defaultParentTpl = General::$loadedModules['Pages']->pathViews . 'pages_parent_html.php';
        //Log::dump('SEParsing', $context);
        parent::__construct('SEParsing', $context);
    }

    function  input() {
        $t = $this->context;
        include($this->defaultParentTpl);
    }
}
