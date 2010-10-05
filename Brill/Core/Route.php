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
class parsingQuery {
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

    public static function set($key, $value) {
        if (is_null(self::${$key})) {
                self::${$key} = $value;
        }
    }

    public static function  __get($name) {
        return self::${$name};
    }

  }
