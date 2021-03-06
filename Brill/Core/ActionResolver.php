<?php
/*
 * Поиск модуля, экшена и
 *
 * @author almaz
 */

class ActionResolver {
    
    function  __construct() {
        //наверно пусть делает запрос на кэш
    }

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
            $classAction = $route->action;
        } else {
            $classAction = $module->defaultAction;
        }

        $route->set('action', $classAction);
        $classAction = 'a' . $classAction;
        
        $filePath = MODULES_PATH . $route->module . $sep . General::NAME_DIR_ACTIONS . $sep . $classAction . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            if (class_exists($classAction)) {
                $action = new $classAction($module, $route->act);
                if (!is_subclass_of($action, 'Action')) {
                    Log::warning($classAction . ' должен быть унаследован от Action');
                }
                General::runEvent(GENERAL::EVENT_AFTER_CONSTRUCT_ACTION);

                if ($action->session->is('userInfo')) {
                    $action->userInfo = $action->session->get('userInfo');
                } else {
                    $action->userInfo = null;
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
        $sep = DIRECTORY_SEPARATOR;
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
                if (!is_subclass_of($action, 'Action')) {
                    Log::warning($classAction . ' должен быть унаследован от Action');
                }
                General::runEvent(GENERAL::EVENT_AFTER_CONSTRUCT_ACTION_INTERNAL);
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
}
