<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegistryContext
 *
 * @author almaz
 */

require_once 'Registry.php';
class RegistryContext extends Registry {
    protected static $instance = null;
    final public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }

    function  __construct() {

    }

    function simpleAccessToVars() {
        foreach ($this->values as $key => $value) {
            if(!is_array($value) && !is_object($value)) {
                define('_' . $key, $value);
            } else {

            }

        }
    }

    function  __get($field) {
        if (array_key_exists($field, $this->values)) {
             return $this->values[$field];
        } else {
            Log::warning('Не возможно получить свойство. / ' . get_class($this) .'->' . $field . ' - не определено');
        }

    }
}