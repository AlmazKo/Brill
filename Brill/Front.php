<?php
/*
 * Класс
 */


/**
 * Description of Front
 *
 * @author almaz
 */

require 'Config.php';


class Front {

    private static $defaultAction;
    static function run() {
        $instance = new Front();
        $instance->init();
        $instance->handleRequest();
    }

    /**
     * Подгрузка модулей и прочего
     *
     */
    private function init() {

        self::loadModules();
        self::CheckingDependency();
 #       self::$defaultAction = 'Welcome';
    }

    /**
     * Загрузка конфигураций всех модулей
     */
    private static function loadModules() {
        $dirsModules = scandir(MODULES_PATH);
        foreach ($dirsModules as $nameModule) {
            if ($nameModule != '.' && $nameModule != '..') {
                $pathModule = MODULES_PATH.$nameModule . '/' . $nameModule . '.php';
                if (file_exists($pathModule)) {
                    require_once $pathModule;
                    // working in php >= 5.2.x
                    General::$loadedModules[$nameModule] = call_user_func(array($nameModule, 'instance'));
                    /*
                     * TODO более простой код, но только для php >=5.3.x
                     * General::$loadedModules[$nameModule] = $nameModule::instance();
                     */
                }else {
                   Log::warning('Не найден модуль: '.$nameModule);
                }
            }
        }
    }

    /**
     * Проверяет зависимости у модулей
     */
    private static function CheckingDependency() {
        if (General::$loadedModules) {
            foreach (General::$loadedModules as $nameModule => $module) {
                foreach ($module->requiredModules as $requireModule){
                    if ($requireModule) {
                        if (!isset(General::$loadedModules[$requireModule])) {
                            Log::warning('Модулю '.$nameModule.' требуется модуль '.$requireModule);
                        }
                    }
                }
            }
        } else {
            Log::warning('Не найдено не одно модуля');
        }
    }

    /**
     * Запускает экшен
     */
    private function handleRequest() {
        $request = RegistryRequest::instance();
//        $context = RegistryContext::instance();
        $actR = new ActionResolver();
        $act = $actR->getAction($request);
        if (General::$loadedModules['Auth']) {
            //$auth = ne;
        }

        $act->execute();
    }
}