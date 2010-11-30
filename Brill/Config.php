<?php
/*
 * Настройки текущего проекта
 */
// не забываем поправить .htaccess
define ('WEB_PREFIX', '/');
Log::setLevel(1);
//Установка дефолтного модуля
General::$defaultModule = 'Pages';

RegistryDb::instance()->setSettings(DB::DEFAULT_LNK, array('localhost', 'root', '', 'brill'));
DB::connect();


#DB::query("");
