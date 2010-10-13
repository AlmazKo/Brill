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
class ParsingQuery {
    private $schema;
    private $site;
    private $core;
    private $module;
    private $action;
    private $act;
    private $queryString;
    private $search;
    private $nav;
    private $fragment;

    protected static $instance = null;

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

  }
