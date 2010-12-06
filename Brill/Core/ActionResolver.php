<?php
/*
 * Поиск модуля, экшена и
 *
 * @author almaz
 */
require_once CORE_PATH . 'Actions/Action.php';
require_once CORE_PATH . 'Routing.php';
require_once CORE_PATH . 'InternalRoute.php';

class ActionResolver {

    /**
     * Найти и вернуть экшен
     * @param RegistryRequest $request
     * @return Action
     */
    public function getAction (RegistryRequest $request) {

        /*
         * парсим строку
         * узнаем есть ли модуль
         * узнаем есть ли экшен
         * узнаем есть ли акт
         *
         */
        $route = Routing::instance();
        $route->parse();
        $sep = '/';

        // если не указан в запросе модуль, берем дефолтный
        if (!$route->module) {
            $route->set('module', General::$defaultModule);
        }

        //узнаем загружен ли модуль (конфиг модуля)
        if (!isset(General::$loadedModules[$route->module])) {
            Log::warning('Конфигурация модуля ' .$route->module .' не загружена в систему');
        }
        // получаем экземляр класса
        $module = General::$loadedModules[$route->module];

        // указан ли в запросе экшен, если нет - берется дефолтный для текущего модуля
        if ($route->action) {
            $classAction = 'a' . $route->action;
        } else {
            $classAction = $module->defaultAction;
        }

        $filePath = MODULES_PATH . $route->module . $sep . General::NAME_DIR_ACTIONS . $sep . $classAction . '.php';
       
        if (file_exists($filePath)) {
            require_once $filePath;
            if (class_exists($classAction)) {
                $action = new $classAction($module, $route->act);
                if (!is_subclass_of($action, 'Action')) {
                    Log::warning($classAction . ' должен быть унаследован от Action');
                }
                $action->nav = $route->nav;
                $action->search = $route->search;
                $action->route = $route;
                return $action;
            } else {
                 Log::warning('Не найден класс '.$classAction);
            }
        } else {
            Log::warning('Не найден файл: '.$filePath);
        }
    }

    /**
     * Метод получает экшен
     * для внутренней работы между модулями
     *
     * @param InternalRouter $route
     * @return classAction
     */
    public function getInternalAction(InternalRoute $iRoute) {
        $route = Routing::instance();
        $route->syncWithIRoute($iRoute);
        $sep = '/';
        //узнаем загружен ли модуль (конфиг модуля)
        if (!isset(General::$loadedModules[$route->module])) {
            Log::warning('Конфигурация модуля ' .$route->module .' не загружена в систему');
        }
        // получаем экземляр класса
        $module = General::$loadedModules[$route->module];
        $classAction = 'a' . $route->action;
        $filePath = MODULES_PATH . $route->module . $sep . General::NAME_DIR_ACTIONS . $sep . $classAction . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            if (class_exists($classAction)) {

                $action = new $classAction($module, $route->act, true);
                $action->nav = $route->nav;
                $action->search = $route->search;
                $action->route = $route;
                //TODO проверка subclass От Actions
                return $action;
            } else {
                 Log::warning('Не найден класс '.$classAction);
            }
        } else {
            Log::warning('Не найден файл: '.$filePath);
        }
       
    }

    function  __construct() {
        //наверно пусть делает запрос на кэш
    }
}
