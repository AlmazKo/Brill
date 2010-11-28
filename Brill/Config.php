<?php
/* 
 * Настройки текущего проекта
 */
// не забываем поправить .htaccess
define ('WEB_PREFIX', '/ba/');
Log::setLevel(1);
//Установка дефолтного модуля
General::$defaultModule = 'Pages';

RegistryDb::instance()->setSettings(DB::DEFAULT_LNK, array('localhost', 'root', '12345', 'brill'));
DB::connect();


#DB::query("");
