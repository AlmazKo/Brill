<?php
/*
 * SEParsing
 * модуль по парсингу поисковых систем
 * а также для работы с ними
 */

class SEParsing extends Modules {
    protected static $instance = null;
    protected $version = 1;
    protected $prefix = "sep_";
    protected $name = __CLASS__;
    protected $defaultAction = 'aKeywords';
    protected $pathModels = null;
    protected $pathActions = null;
    protected $pathViews = null;

    public function getSettingsAccess() {
        $rights = array();
        $rights[Access::GROUP_USER] = array(
            'Sets'=> array('View','Add','Edit', 'Del'),
            'Regions' => array('Add', 'View', 'Edit', 'Del'),
            'Thematics' => array('Add', 'View', 'Edit', 'Del'),
            'Keywords' => array('Thematic', 'Pos', 'Set', 'View', 'Add', 'MassAdd'),

        );
        $rights[Access::GROUP_MANAGER] = array(
            'Sets'=> array('View'),
            'Regions' => array('View'),
            'Thematic' => array('View'),
            'Thematics' => array('View'),
            'Keywords' => array('Thematic', 'Pos', 'Set', 'View'),
            );
        return $rights;
    }
    protected function configure() {
        require_once $this->pathDB . 'se_Stmt.php';
 
    }

    /**
     * Подключает важные файлы, до инициализации демона
     */
    function configureDaemon() {
        require_once $this->pathDaemons . 'se_Parser.php';
        require_once $this->pathDB . 'se_StmtDaemon.php';
        require_once $this->pathLib . 'se_Lib.php';
    }

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

}