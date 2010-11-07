<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegistryParser
 *
 * @author asuslov
 */
require_once 'Registry.php';

class RegistryRequest extends Registry{
    protected $_isAjax = null;
    protected $HTTP_X_REQUESTED_WITH = null;
    protected $_requestUri = '';
    protected $_requestUriWithoutModule;
    protected $nav = array();
    protected $search = array();

    protected static $instance = null;
    final public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Разбираем входящие данные
     */
    protected function __construct() {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            foreach ($_REQUEST as $key => $val) {

            $this->set($key, $val);
            }
            //инициализация _POST
            if ($_POST) {
                $post = array();
                foreach ($_POST as $key => $val) {
                    $post[$key] =$val;
                }
                $this->set('POST', $post);
            } else {
                $this->set('POST', null);
            }
            //инициализация _GET
            if ($_GET) {
                $get = array();
                foreach ($_GET as $key => $val) {
                    $get[$key] = $val;//TODO добавить экранирование И ПОДДЕРЖКУ МАССИВОВ!!!!
                }
                $this->set('GET', $get);
            } else {
                $this->set('GET', null);
            }
            $this->HTTP_X_REQUESTED_WITH = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;

        } else if(isset($_SERVER['argv'])) {
            foreach ($_SERVER['argv'] as $arg) {
                if (strpos($arg, '=')) {
                    list($key, $val) = explode("=", $arg);
                    $this->set(strtolower($key), $val);
                }
            }
        }
    }

    /*
     * обвертка isXmlHttpRequest
     *
     * TODO
     * если будем делать поддержку некоторых серверов(а-ля Denwer), не реализующих эту штуку
     * для всех запросов будет добавляться, например ajax=1
     */
    public function isAjax() {
        if (isset($this->_isAjax)) {
            return $this->_isAjax;
        }
        if ($this->isXmlHttpRequest() or $this->get('ajax') == '1') { //ADD чтото вроде этого: if
            $this->_isAjax = true;
            return true;
        } else {
            $this->_isAjax = false;
        }
        return $this->_isAjax;
    }

    public function isXmlHttpRequest() {
           return  $this->HTTP_X_REQUESTED_WITH == 'XMLHttpRequest';
    }
    public function getUri ($string) {
        return $this->requestUri;
    }

    public function setUri ($string) {
        if (empty($this->_requestUri)) {
           $this->_requestUri = $string;
        }
    }
    /**
     * расширенная версия get. позволяющая работать с массивами
     * @param <type> $key
     * @param <type> $key2
     * @return <type>
     */
    public function get($key, $key2 = null) {
        if (isset($this->values[$key])) {
            if ($key2 !== null) {
                if (isset($this->values[$key][$key2])) {
                    return $this->values[$key][$key2];
                } else {
                     Log::warning("[$key]['$key2'] нет такого свойства");
                }

            } else {
                return $this->values[$key];
            }
            
        } else {
            Log::warning("'$key' нет такого свойства");
        }
    }

}