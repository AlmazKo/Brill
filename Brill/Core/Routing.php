<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Класс распарсенной строки
 *
 * @author Alexander
 */
class Routing {
    private $uri;
    private $schema;
    private $site;
    private $core;
    private $module;
    private $action;
    private $act;
    private $queryString;
    private $search;
    private $nav;

    protected static $instance = null;

    private function  __construct() {
        $this->uri = $_SERVER["REQUEST_URI"];
    }
    
    final public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

    public function set($key, $value) {
    //    if (is_null($this->$key)) {
                $this->$key = $value;
     //   }
    }

    public function  __get($name) {
        return $this->$name;
    }


    /**
     * Определяет правило для разбиения
     * core/module/action/act/nav
     */
    function parse ($uri = false) {
        if (!$uri) {
            $uri = $this->uri;
        } else {
            $this->uri = $uri;
        }

        /*
         * Одна из возможных стратегий
         * В это - каждый эелемент должен заканчиваться на слэш
         *
         */
        $searchStrategy = '(?:search(&[a-zA-Z0-9_-]+\=[^\/]*)+\/)?';
        $navStrategy = '(?:nav(?:&([a-z]+\=[^\/]*))+\/)?';
        $stdRule = '/\/(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?(?:([a-zA-Z0-9]+)\/)?'.$searchStrategy.$navStrategy.'/';
        if (preg_match($stdRule, $uri, $m)) {
            $this->site = $_SERVER['HTTP_HOST'];
            $this->core = isset($m[1]) ? $m[1] : '';
            $this->module = isset($m[2]) ? $m[2] : '';

            $this->action = isset($m[3]) ? $m[3] : '';
            $this->act =  isset($m[4]) ? $m[4] : '';
            $this->search = isset($m[5]) ? $m[5] : '';
            if (isset($m[6])) {
                $nav_params = explode('&', $m[6]);
                foreach ($nav_params as $param) {
                    $par = explode('=', $param);
                    $navigation[$par[0]] = $par[1];
                }
                Navigation::set($navigation);
                $this->nav = $m[6];
            } else {
                $this->nav = '';
            }
            $this->queryString = $_SERVER['QUERY_STRING'];
            return true;
        } else {
            return false;
        }
    }

    /**
     * Конструирует строку нового урла
     * @param array $parts массив изменяемых частей роутинга
     * @return string
     */
    public static function constructUrl($parts) {
        $route = self::instance();
        $url  = '/';
        $url .= $route->core ? $route->core . '/' : '';
        $url .= isset($parts['module']) ? $parts['module'] . '/' : $route->module ? $route->module . '/' : '';
        $url .= isset($parts['action']) ? $parts['action'] . '/' : $route->action ? $route->action . '/' : '';
        $url .= isset($parts['act']) ? $parts['act'] . '/' : $route->act ? $route->act . '/' : '';

        if (isset($parts['nav']) && is_array($parts['nav'])) {
            //получаем новый массив путем пересечения. оставляем только те поля, которое могут быть
            $newNav = array_intersect_key($parts['nav'],  Navigation::getArray(true));
            if ($newNav) {
                $navStr = '';
                foreach($newNav as $key => $value) {
                    $navStr .= '&' . $key . '=' . $value;
                }
                $url .= 'nav' . $navStr . '/';
            }
        } else {
            $url .= isset($parts['nav']) ? $parts['nav'] . '/' : $route->nav ? $route->nav . '/' : '';
        }
        return  $url . ($route->queryString ? '?' . $route->queryString : '');
    }
}
