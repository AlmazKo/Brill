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
        self::$defaultAction = 'Welcome';
    }

    /**
     * Проверяет все модули и подгружает их файлы
     */
    private static function loadModules() {
        $dirsModules = scandir(MODULES_PATH);
        foreach ($dirsModules as $nameModule) {
            if ($nameModule != '.' && $nameModule != '..') {
                $pathModule = MODULES_PATH.$nameModule . '/' . $nameModule . '.php';
                if (file_exists($pathModule)) {
                    require_once $pathModule;
                    General::$loadedModules[$nameModule]['required'] = $nameModule::$requiredModules;
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
            foreach (General::$loadedModules as $module => $settings) {
                if (is_array($settings['required'])) {
                    foreach ($settings['required'] as $requireModule){
                        if ($requireModule) {
                            if (!isset(General::$loadedModules[$requireModule])) {
                                Log::warning('Модулю '.$module.' требуется модуль '.$requireModule);
                            }
                        }

                    }
                }
            }
        }
    }

    private function handleRequest() {
        $request = RegistryRequest::instance();
        $context = RegistryContext::instance();
        $actR = new ActionResolver(); 
        $act = $actR->getAction($request);
        $act->execute($context);
    }
}