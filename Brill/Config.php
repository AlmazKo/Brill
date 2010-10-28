<?php
/*
 * ПОдключение необходимых файлов и общая настройка
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
@ini_set ('pcre.backtrack_limit', '5000000');
@ini_set('max_execution_time',0);
@ini_set("max_input_time",0);
@set_time_limit(0);
 *
 * задать все нормальные лимиты
 */
header('Content-type: text/html; charset=utf-8');
define ('DIR_PATH', str_replace("\\", "/", realpath (dirname (__FILE__))));
define ('CORE_PATH',DIR_PATH . '/Core/');
define ('MODULES_PATH',DIR_PATH . '/Modules/');
$dirs [] = '.';
$dirs [] = DIR_PATH . '/Common';
//$dirs [] = DIR_PATH . '/DB';
//$dirs [] = DIR_PATH . '/Interfaces';
//$dirs [] = DIR_PATH . '/Commands';
$dirs [] = CORE_PATH . '/Registry';
//$dirs [] = DIR_PATH . '/Models';
//$dirs [] = DIR_PATH . '/Views';
//$dirs [] = DIR_PATH . '/Templates';

set_include_path(implode(PATH_SEPARATOR, $dirs));
require_once CORE_PATH . 'Common/General.php';
require_once CORE_PATH . 'Common/RunTimer.php';
require_once CORE_PATH . 'Common/Encoding.php';
require_once CORE_PATH . 'Common/StringUtf8.php';
require_once CORE_PATH . 'Common/Log.php';
require_once CORE_PATH . 'Common/TFormat.php';
require_once 'RegistrySession.php';
require_once 'RegistryRequest.php';
require_once 'RegistryContext.php';
require_once 'RegistryDb.php';
require_once CORE_PATH . 'DB/DBExt.php';
require_once CORE_PATH . 'Actions/Action.php';
require_once CORE_PATH . 'Models/Model.php';
require_once CORE_PATH . 'Views/View.php';
require_once CORE_PATH . 'Views/View.php';

require_once CORE_PATH . 'Lang/ru/texts.php';

$timer = new RunTimer();

Log::setLevel(1);
RegistryDb::instance()->setSettings('default', array('localhost', 'root', '12345', 'brill'));
DB::connect();

/*
require_once 'DBParsingInterface.php';
require_once 'DBCheckingInterface.php';

require_once 'Front.php';
require_once 'DB.php';
require_once DIR_PATH . '/Lang/ru/texts.php';
 * 
 */

require_once 'RegistryRequest.php';
require_once 'RegistryContext.php';
require_once 'RegistryDb.php';

require CORE_PATH . 'ActionResolver.php';
require CORE_PATH . 'Navigation.php';
require CORE_PATH . 'Interfaces/IPaging.php';
require CORE_PATH . 'Interfaces/ISorting.php';

require CORE_PATH . 'Objects/oList.php';
require CORE_PATH . 'Objects/oTable.php';
require CORE_PATH . 'Objects/oTableExt.php';
require CORE_PATH . 'Objects/oForm.php';
require CORE_PATH . 'Objects/oFormExt.php';
require CORE_PATH . 'Objects/oTree.php';
