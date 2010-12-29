<?php
/*
 * ПОдключение необходимых файлов для демона и общая настройка
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');
define ('ENCODING_CODE', 'utf-8');

header('Content-type: text/html; charset=utf-8');


@set_time_limit(0);

define ('DIR_PATH', str_replace("\\", "/", realpath (dirname (__FILE__) .'/..')));
define ('CORE_PATH', DIR_PATH . '/Core/');
define ('REGISTRY_PATH', CORE_PATH . 'Registry/');
define ('MODULES_PATH', DIR_PATH . '/Modules/');
define ('USE_CACHE', false);

require_once CORE_PATH . 'Common/NewFunctions.php';
require_once CORE_PATH . 'Common/General.php';
require_once CORE_PATH . 'Common/RunTimer.php';
require_once CORE_PATH . 'Common/Encoding.php';
require_once CORE_PATH . 'Common/StringUtf8.php';
require_once CORE_PATH . 'Common/Log.php';
require_once CORE_PATH . 'Common/TFormat.php';

Log::setLevel(0);
//$timer = new RunTimer();
require_once CORE_PATH . 'Underworld.php';
require_once CORE_PATH . 'Modules.php';
require_once REGISTRY_PATH . 'Registry.php';
require_once REGISTRY_PATH . 'RegistrySession.php';
require_once REGISTRY_PATH . 'RegistryRequest.php';
require_once REGISTRY_PATH . 'RegistryContext.php';
require_once REGISTRY_PATH . 'RegistryDb.php';
require_once CORE_PATH . 'DB/DBExt.php';
require_once CORE_PATH . 'Common/LogInDb.php';
require_once CORE_PATH . 'Models/Model.php';
require_once CORE_PATH . 'Lib/Lib.php';
require_once CORE_PATH . 'Lib/Xml.php';
require_once CORE_PATH . 'Lib/DomExt.php';
require_once CORE_PATH . 'DB/Stmt.php';
require_once CORE_PATH . 'Lib/Curl.php';
require_once CORE_PATH . 'Lang/ru/texts.php';

$timer = new RunTimer();
