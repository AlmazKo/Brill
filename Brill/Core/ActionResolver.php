<?php
/*
 * Поиск модуля, экшена и
 *
 * @author almaz
 */
require_once CORE_PATH . 'Actions/Action.php';
require_once CORE_PATH . 'Routing.php';
class ActionResolver {
    private static $defaultModule = 'Pages';



    /**
     * Найти и вернуть экшен
     * @param RegistryRequest $request
     * @return Action
     */
    public function getAction (RegistryRequest $request) {
        $route = Routing::instance();
        $route->parse();
        $sep = '/';
        if (!$route->module) {
            $route->set('module', self::$defaultModule);
        }

        $module = $route->module;
        if ($route->action) {
            $classAction = 'a' . $route->action;
        } else {
            $classAction = $module::$defaultAction;
        }

        $filePathModule = MODULES_PATH . $module . $sep . $module . '.php';

        if (file_exists($filePathModule)) {
            require_once $filePathModule;
        }else {
            Log::warning('Не найден файл: '.$filePathModule);
        }

       
        General::$route = $route;

        $filePath = MODULES_PATH . $module . $sep . 'Actions' . $sep . $classAction . '.php';

        if (file_exists($filePath)) {
            //иницилизируем настройки модуля
            $gModule = new $module();

       //     $module::init();
            require_once $filePath;
            if (class_exists($classAction)) {
            $action = new $classAction($route->act);
            $action->nav = $route->nav;
            $action->search = $route->search;

                //ADD проверка subclass От Actions
                return $action;
            }
        } else {
            Log::warning('Не найден файл: '.$filePath);
        }

    }

    function  __construct() {
        //наверно пусть делает запрос на кэш
        //задаючтся еще дефолтные экшены (из ядра)
        //self::$defaultAction = new
    }
}
