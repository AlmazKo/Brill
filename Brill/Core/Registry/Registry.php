<?php

/**
 * Description of Registry
 *
 * @author asuslov
 */

abstract class Registry{

    protected $values = array();

    /**
     * Удаляет все свойства
     */
    public function clean () {
        $this->values = array();
    }

    public function get($key) {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        } else {
            Log::warning("'$key' нет такого свойства");
        }
    }

    public function is($key) {
        if (isset($this->values[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Добавить значение в реестр
     * 
     * @param mixed $key - имя ключа
     * @param mixed $value - значение
     * @param bool $force - перезаписывать ли данные?
     */
    public function set($key, $value, $force = true) {
        if ($force) {
            $this->values[$key] = $value;
        } else {
            if (!$this->is($key)) {
                $this->values[$key] = $value;
            }
        }
    }

}