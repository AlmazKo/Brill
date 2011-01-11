<?php
/*
 * ПОдключение необходимых файлов и общая настройка
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');
define ('ENCODING_CODE', 'utf-8');
header('Content-type: text/html; charset=' . ENCODING_CODE);

//TODO сделать нормальную для винды
define ('DIR_PATH', str_replace("\\", "/", realpath (dirname (__FILE__) .'/..')) . '/');

//путь к директории ядра
define ('CORE_PATH', DIR_PATH . 'Core/');
// путь к директории с модулями
define ('MODULES_PATH', DIR_PATH . 'Modules/');
// включит ли кэширование css/js
define ('USE_CACHE', false);

define ('CORE_VERSION', '0.2');

#Это и так происходит//относительные пути также идут и от корня сайта
#set_include_path(DIR_PATH);

require CORE_PATH . 'Common/NewFunctions.php';
require CORE_PATH . 'General.php';
require CORE_PATH . 'Common/Timer.php';
require CORE_PATH . 'Common/RunTimer.php';
require CORE_PATH . 'Common/Encoding.php';
require CORE_PATH . 'Common/StringUtf8.php';
require CORE_PATH . 'Common/Log.php';
require CORE_PATH . 'Common/LogMysql.php';
require CORE_PATH . 'Common/TFormat.php';
require CORE_PATH . 'Common/Error.php';
$timer = new RunTimer();

require CORE_PATH . 'Registry/Registry.php';
require CORE_PATH . 'Registry/RegistrySession.php';
require CORE_PATH . 'Registry/RegistryRequest.php';
require CORE_PATH . 'Registry/RegistryContext.php';
require CORE_PATH . 'Registry/RegistryDb.php';
require CORE_PATH . 'Modules.php';
require CORE_PATH . 'DB/DB.php';
require CORE_PATH . 'DB/DBExt.php';
require CORE_PATH . 'DB/Stmt.php';
require CORE_PATH . 'Common/LogInDb.php';
require CORE_PATH . 'Actions/Action.php';
require CORE_PATH . 'Models/Model.php';
require CORE_PATH . 'Views/View.php';
require CORE_PATH . 'Lib/Lib.php';
require CORE_PATH . 'Lib/Curl.php';
require CORE_PATH . 'Lib/DomExt.php';
require CORE_PATH . 'Lang/ru/texts.php';

require CORE_PATH . 'Routing.php';

require CORE_PATH . 'ActionResolver.php';
require CORE_PATH . 'Navigation.php';
require CORE_PATH . 'InternalRoute.php';

require CORE_PATH . 'Interfaces/IPaging.php';
require CORE_PATH . 'Interfaces/ISorting.php';

require CORE_PATH . 'Objects/oList.php';
require CORE_PATH . 'Objects/oTable.php';
require CORE_PATH . 'Objects/oTableExt.php';
require CORE_PATH . 'Objects/oForm.php';
require CORE_PATH . 'Objects/oFormExt.php';
require CORE_PATH . 'Objects/oTree.php';

//пользовательские настройки
require DIR_PATH . 'Config.php';

DB::connect();