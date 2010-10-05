<?php
/* 
 * Поиск модуля, экшена и
 *
 * @author almaz
 */
require_once CORE_PATH . 'Actions/Action.php';
require_once CORE_PATH . 'Route.php';
class ActionResolver {
    private static $defaultAction;

    /*
     * Определяет правило для разбиения
     * core/module/action/act/nav
     */
    public static function routing($uri) {
        $route = new Route();
        /*
         * Одна из возможных стратегий
         * В это - каждый эелемент должен заканчиваться на слэш
         *
         */
        $searchStrategy = '(?:search(&[a-zA-Z0-9_-]+\=[^\/]*)+\/)?';
        $navStrategy = '(?:nav(&[a-z]+\=[^\/]*)+\/)?';
        $stdRule = '/\/(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?'.$searchStrategy.$navStrategy.'/';
        if (preg_match($stdRule, $uri, $m)) {
            $route->site = $_SERVER['HTTP_HOST'];
            $route->core = isset($m[1]) ? $m[1] : '';
            $route->module = isset($m[2]) ? $m[2] : '';
            $route->action = isset($m[3]) ? $m[3] : '';
            $route->act = isset($m[4]) ? $m[4] : '';
            $route->search = isset($m[5]) ? $m[5] : '';
            $route->nav = isset($m[6]) ? $m[6] : '';
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
        $filePath = MODULES_PATH . $route->module . $sep . 'Actions' . $sep . $route->action . '.php';
        $className = $route->action;
        if (file_exists($filePath)) { 
            @require_once $filePath;
            if (class_exists($className)) {
                $act = new $className($route->act);
                //ADD проверка subclass От Actions
                return $act;
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
