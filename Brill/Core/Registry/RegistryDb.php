<?php
/**
 * Класс настроек для работы с БД
 *
 * @author almazko
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

    /**
     * Добавление нового соединения
     * @param array $dbSettings массив настроек, вида array('user','password', 'host', 'db')
     * @param strng $lnk название соединения
     */
    public function createConnect($dbSettings, $lnk = DB::DEFAULT_LNK) {
        if (is_array($dbSettings)) {
            if (count($dbSettings) == 4) {
                $this->set($lnk, $dbSettings);
            } else {
                Log::warning("Конфигурация соединения с БД должна быть такого вида:\n".
                        "array('user','password', 'host', 'db')");
            }
        } else {
            Log::warning('Настройки для работы с БД должны быть указаны в виде массива');
        }
    }
}
