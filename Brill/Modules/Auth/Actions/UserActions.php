<?php
/* 
 * Action Registration's
 *
 * @author Alexander
 */
class UserActions extends Action{
   protected $defaultAct = 'LogIn';

    protected function configure() {
        require_once $this->module->pathModels . 'sep_Keywords.php';
        require_once $this->module->pathModels . 'sep_Thematics.php';
        require_once $this->module->pathModels . 'sep_Sets.php';
        require_once $this->module->pathModels . 'sep_Regions.php';
        require_once $this->module->pathModels . 'sep_UrlKeywords.php';
        require_once MODULES_PATH . 'SEParsing/DB/Stmt.php';
    }

    public function act_Registration() {

    }

    public function act_LogIn() {

    }

    public function act_CheckEmail() {

    }

    public function act_LogOut() {
        
    }

    public function act_FogottonPass() {
        
    }

    
}

