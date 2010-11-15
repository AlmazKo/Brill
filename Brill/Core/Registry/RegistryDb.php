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

/**
 * Класс настроек для подключения к Базе
 */
class RegistryDb extends Registry{

    protected static $instance = null;
    final public  static function instance() {
        if (self::$instance === null) {
           self::$instance = new self();
        }
        return self::$instance;
    }


    public function setSettings($name, $dbSettings) {
        if (is_array($dbSettings)) {
            if (count($dbSettings) == 4) {
                $this->set($name, $dbSettings);
            } else {
                Log::warning("Конфигурация соединения с БД должна быть такого вида:\n".
                        "array('user','password', 'host', 'db')");
            }
        } else {
            Log::warning('Настройки для работы с БД должны быть указаны в виде массива');
        }
    }
}
