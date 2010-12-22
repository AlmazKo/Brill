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
    protected $defaultAction = 'Keywords';
    protected $pathModels = null;
    protected $pathActions = null;
    protected $pathViews = null;

    /**
     * дефольные значения прав доступа на модуль
     * @return array
     */
    public function getSettingsAccess() {
        $rights = array();
        $rights[General::GROUP_MANAGER] = array(
            'Sets'=> array('View','Add','Edit', 'Del'),
            'Regions' => array('Add', 'View', 'Edit', 'Del'),
            'Thematics' => array('Add', 'View', 'Edit', 'Del'),
            'Keywords' => array('All', 'Thematic', 'Pos', 'Set', 'View', 'Add', 'MassAdd'),

        );
        $rights[General::GROUP_USER] = array(
            'Sets'=> array('View'),
            'Regions' => array('View'),
            'Thematic' => array('View'),
            'Thematics' => array('View'),
            'Keywords' => array('All', 'Thematic', 'Pos', 'Set', 'View'),
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
        require_once $this->pathDaemons . 'se_YandexXml.php';
        require_once $this->pathDB . 'se_StmtDaemon.php';
        include_once MODULES_PATH . 'Settings/' . General::NAME_DIR_DB. '/st_StmtDaemon.php';
        include_once MODULES_PATH . 'Settings/' . General::NAME_DIR_LIB. '/st_Lib.php';
    }

    public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

}