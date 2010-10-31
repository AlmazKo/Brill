<?php
/**
 * Description of 
 *
 * @author almaz
 */



class vPages extends View {
     protected $defaultParent = 'pages_parent_html.php';
     protected $useParent = true;
     protected $aHeaders = array();
     protected $context;

    // выводит хидеры
    function inputHeaders ($status = '200') {

    }

    public function  __construct(RegistryContext $context) {
        $this->defaultParentTpl = General::$loadedModules['Pages']->pathViews . 'pages_parent_html.php';
        parent::__construct('Pages', $context);
    }

    function  input() {
        $t = $this->context;
        include( $this->defaultParent);
    }
}
