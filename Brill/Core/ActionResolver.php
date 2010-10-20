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
        $navStrategy = '(?:nav(?:&([a-z]+\=[^\/]*))+\/)?';
        $stdRule = '/\/(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?'.$searchStrategy.$navStrategy.'/';
        if (preg_match($stdRule, $uri, $m)) {
            $route->set('site', $_SERVER['HTTP_HOST']);
            $route->set('core', isset($m[1]) ? $m[1] : '');
            $route->set('module', isset($m[2]) ? $m[2] : '');
            $route->set('action', isset($m[3]) ? $m[3] : '');
            $route->set('act', isset($m[4]) ? $m[4] : '');
            $route->set('search', isset($m[5]) ? $m[5] : '');
            if (isset($m[6])) {
                $nav_params = explode('&', $m[6]);
                foreach ($nav_params as $param) {
                    $par = explode('=', $param);
                    $navigation[$par[0]] = $par[1];
                }
                $route->set('nav', $navigation);
            } else {
                $route->set('nav', '');
            }
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
        if ($route->action) {
            $route->set('action', 'a' . $route->action);
        } else {
            $route->set('action', $module::$defaultAction);
        }

        $filePathModule = MODULES_PATH . $module . $sep . $module . '.php';

        if (file_exists($filePathModule)) {
            require_once $filePathModule;
        }else {
            Log::warning('Не найден файл: '.$filePathModule);
        }

       
        General::$route = $route;

        $filePath = MODULES_PATH . $module . $sep . 'Actions' . $sep . $route->action . '.php';

        $className = $route->action;
        if (file_exists($filePath)) {
            //иницилизируем настройки модуля
            $gModule = new $module();

       //     $module::init();
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
