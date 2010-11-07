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
    protected 
        $defaultTpl = 'subscribe_parent_html.php',
        $dirTemplates,
        $useParentTpl = false;

    public function  __construct(RegistryContext $context) {
        $this->defaultTpl = General::$loadedModules['Pages']->pathViews . 'pages_parent_html.php';
        
        parent::__construct('AutoSubmitter', $context);
    }

}

