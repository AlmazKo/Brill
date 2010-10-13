<?php
/*
 * Поиск модуля, экшена и
 *
 * @author almaz
 */
require_once CORE_PATH . 'Actions/Action.php';
require_once CORE_PATH . 'ParsingQuery.php';
class ActionResolver {
    private static $defaultModule = 'Pages';

    /*
     * Определяет правило для разбиения
     * core/module/action/act/nav
     */
    public static function routing($uri) {
        $route = ParsingQuery::instance();
        /*
         * Одна из возможных стратегий
         * В это - каждый эелемент должен заканчиваться на слэш
         *
         */
        $searchStrategy = '(?:search(&[a-zA-Z0-9_-]+\=[^\/]*)+\/)?';
        $navStrategy = '(?:nav(&[a-z]+\=[^\/]*)+\/)?';
        $stdRule = '/\/(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?'.$searchStrategy.$navStrategy.'/';
        if (preg_match($stdRule, $uri, $m)) {
            $route->set('site', $_SERVER['HTTP_HOST']);
            $route->set('core', isset($m[1]) ? $m[1] : '');
            $route->set('module', isset($m[2]) ? $m[2] : '');
            $route->set('action', isset($m[3]) ? $m[3] : '');
            $route->set('act', isset($m[4]) ? $m[4] : '');
            $route->set('search', isset($m[5]) ? $m[5] : '');
            $route->set('nav', isset($m[6]) ? $m[6] : '');
            return $route;
        } else {
            return null;
        }
    }

    /**
     * Найти и вернуть экшен
     * @param RegistryRequest $request
     * @return Action
     */
    public function getAction (RegistryRequest $request) {

        $route = self::routing($_SERVER["REQUEST_URI"]);
        $sep = '/';
        if (!$route->module) {
            $route->set('module', self::$defaultModule);
        }
        $module = $route->module;

        if (empty($route->action)) {
            $filePathModule = MODULES_PATH . $module . $sep . $module . '.php';
            if (file_exists($filePathModule)) {
          require_once $filePathModule;
            $route->set('action', $module::$defaultAction);
            }else {
            Log::warning('Не найден файл: '.$filePathModule);
        }

        }
        $filePath = MODULES_PATH . $module . $sep . 'Actions' . $sep . $route->action . '.php';

        $className = $route->action;
        if (file_exists($filePath)) {
            

            //иницилизируем настройки модуля
            $module::init();
            require_once $filePath;
            if (class_exists($className)) {
                $action = new $className($route->act);
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
