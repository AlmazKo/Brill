<?php
/*
 * ПОдключение необходимых файлов и общая настройка
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-type: text/html; charset=utf-8');
define  ('ENCODING_CODE', 'utf-8');
//TODO сделать нормальную для винды
define ('DIR_PATH', str_replace("\\", "/", realpath (dirname (__FILE__) .'/..')));

define ('CORE_PATH', DIR_PATH . '/Core/');
define ('MODULES_PATH', DIR_PATH . '/Modules/');
//префикс домена, где он лежит. если прмо в корне. тут должен быть только слэш
define ('USE_CACHE', false);
$dirs [] = '.';
$dirs [] = CORE_PATH . '/Registry';

set_include_path(implode(PATH_SEPARATOR, $dirs));
require_once CORE_PATH . 'Common/General.php';
require_once CORE_PATH . 'Common/RunTimer.php';
require_once CORE_PATH . 'Common/Encoding.php';
require_once CORE_PATH . 'Common/StringUtf8.php';
require_once CORE_PATH . 'Common/Log.php';
require_once CORE_PATH . 'Common/TFormat.php';

$timer = new RunTimer();

require_once 'Registry.php';
require_once 'RegistrySession.php';
require_once 'RegistryRequest.php';
require_once 'RegistryContext.php';
require_once 'RegistryDb.php';
require_once CORE_PATH . 'Modules.php';
require_once CORE_PATH . 'DB/DBExt.php';
require_once CORE_PATH . 'DB/Stmt.php';
require_once CORE_PATH . 'Common/LogInDb.php';
require_once CORE_PATH . 'Actions/Action.php';
require_once CORE_PATH . 'Models/Model.php';
require_once CORE_PATH . 'Views/View.php';
require_once CORE_PATH . 'Lib/Lib.php';
require_once CORE_PATH . 'Lib/Curl.php';
require_once CORE_PATH . 'Lang/ru/texts.php';

require CORE_PATH . 'ActionResolver.php';
require CORE_PATH . 'Navigation.php';
require_once CORE_PATH . 'InternalRoute.php';

require CORE_PATH . 'Interfaces/IPaging.php';
require CORE_PATH . 'Interfaces/ISorting.php';

require CORE_PATH . 'Objects/oList.php';
require CORE_PATH . 'Objects/oTable.php';
require CORE_PATH . 'Objects/oTableExt.php';
require CORE_PATH . 'Objects/oForm.php';
require CORE_PATH . 'Objects/oFormExt.php';
require CORE_PATH . 'Objects/oTree.php';
