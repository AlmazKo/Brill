<?php
/**
 * Класс для работы с урлом
 *
 * @author almazko
 */
class Routing {
    private
        $_iroute = false,
        $uri,
        $schema,
        $site,
        $core,
        $module,
        $action,
        $act,
        $queryString,
        $search,
        $nav,
        $get;

    protected static $instance = null;

    function  __construct() {
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->get = $_GET;
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
         * В этой - каждый элемент должен заканчиваться на слэш
         *
         */
        $searchStrategy = '(?:search(&[a-zA-Z0-9_-]+\=[^\/]*)+\/)?';
        $navStrategy = '(?:nav(?:&([a-z]+\=[^\/]*))+\/)?';

        if (WEB_PREFIX == '/') {
            $stdRule = '/(\/)?(?:([a-zA-Z0-9_]+)\/)?(?:([a-zA-Z0-9_]+)\/)?(?:([a-zA-Z0-9_]+)\/)?'.$searchStrategy.$navStrategy.'/';
        } else {
            $stdRule = '/\/(?:([a-zA-Z0-9_]+)\/)?(?:([a-zA-Z0-9_]+)\/)?(?:([a-zA-Z0-9_]+)\/)?(?:([a-zA-Z0-9_]+)\/)?'.$searchStrategy.$navStrategy.'/';
        }
        if (preg_match($stdRule, $uri, $m)) {
            $this->site = $_SERVER['HTTP_HOST'];
            $this->core = isset($m[1]) ? $m[1] : '';
            $this->module = isset($m[2]) ? $m[2] : '';
            $this->action = isset($m[3]) ? $m[3] : '';
            $this->act = isset($m[4]) ? $m[4] : '';
            $this->search = isset($m[5]) ? $m[5] : '';
            if (isset($m[6])) {
                $navParams = explode('&', $m[6]);
                foreach ($navParams as $param) {
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
     * @param bool $useQueryString Использовать ли текущие get-параметры
     * @return string
     */
    public static function constructUrl($parts = null, $useQueryString = true) {
         $route = self::instance();
        if ($parts) {
            $url  = '/';
            if ($route->core == '/') {
                $url .= '';
            } else {
                $url .= $route->core ? $route->core . '/' : '';
            }

            $url .= isset($parts['module']) ? $parts['module'] . '/' : ($route->module ? $route->module . '/' : '');
            $url .= isset($parts['action']) ? $parts['action'] . '/' : ($route->action ? $route->action . '/' : '');
            $url .= isset($parts['act']) ? ($parts['act'] . '/') : ($route->act ? $route->act . '/' : '');

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
            $queryString = '';
            if ($useQueryString && isset($parts['GET']) && is_array($parts['GET'])) {
                $aGet = array_replace_recursive($route->get, $parts['GET']);
                if ($aGet) {
                    $queryString = TFormat::prepareQueryString($aGet);
                }
                $queryString = $queryString ? $queryString : $route->queryString;
            } elseif ($useQueryString) {
                $queryString = $route->queryString;
            }
            return  $url . ($queryString ? '?' . $queryString : '');
        } else {
            return $route->uri;
        }
    }


    /**
     * Редирект
     * @param object $url
     */
    public static function redirect($url = '') {
        if (is_array($url)) {
            $url = self::constructUrl($url);
        }
        $request = RegistryRequest::instance();
        if ($request->isAjax()) {
            //требование сделать редирект всей страниц
            header("HTTP/1.1 303 Update Page");
            die(WEB_PREFIX . $url);
        } else {
            header("HTTP/1.1 303 See other");
            header('Location: ' . WEB_PREFIX . $url);
            die;
        }
    }


    /**
     * Синхронихирует общий роутинг с внутренним
     * @param InternalRoute $iRoute
     */
    public function syncWithIRoute(InternalRoute $iRoute) {
        $this->module = $iRoute->module;
        $this->action = $iRoute->action;
        $this->act = $iRoute->act;
        $this->nav = $iRoute->nav;
        $this->search = $iRoute->search;
        $this->_iroute = true;
    }
}