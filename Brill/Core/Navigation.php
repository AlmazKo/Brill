<?php
/**
 * Класс навигации. Для работы с коллекциями.
 *
 * @author almaz
 */
class Navigation {
    private static $nav = array('order' => null, 'field' => null, 'page' => null, 'offset' => null);

    /**
     * возвращает значение поля
     * @param <type> $key
     * @return
     */
    public static function get($key) {
        if (array_key_exists($key, self::$nav)) {
            return self::$nav[$key];
        } else {
            return false;
        }
    }
    /**
     * Возвращает массив значений
     * @param bool $nulls если false - не возвращает null поля
     */
    public static function getArray($nulls = false){
        if ($nulls) {
            return self::$nav;
        } else {
            $nav = array();
            foreach(self::$nav as $key => $value) {
                if (isset($value)) {
                    $nav[$key] = $value;
                }
            }
            return $nav;
        }
    }
    /**
     * Наполняет себя
     */
    public static function set($array) {
        foreach($array as $key => $val) {
            if (array_key_exists($key, self::$nav)) {
               self::$nav[$key] = $val;
            }
        }
    }
}

