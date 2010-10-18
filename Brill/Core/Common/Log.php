<?php
/**
 * Log
 *
 * Класс для отладки кода
 */

class Log {
    /**
     * уровень и тип отладки, должен задаваться один раз и только в начале
     */
    private static $_debugLevel = null;
    private static $_screen = false;
    private static $_file = false;
    private static $aLog = array();
    private static $i = 0;
    public static function setLevel($num) {
        if (self::$_debugLevel === null) {
            if ($num){
                 switch($num) {
                    case 1:
                        self::$_screen = true;
                        break;
                    case 2:
                        self::$_file = true;
                        break;
                    case 3:
                        self::$_screen = true;
                        self::$_file = true;
                }
            } else {
                self::$_debugLevel = false;
            }
        }
    }

    /**
     * Вывод дампа чего либо
     * @param object $obj
     * @param string $color
     */
    public static function dump($obj, $color = '#00a0ff', $title = 'Dump') {
        ob_start();
        var_dump($obj);
        $descr = ob_get_clean ();
        self::inputLog($title, $descr, true, $color);
    }

    /**
     * Вывод  чего либо
     * @param object $obj
     * @param string $color
     */
    public static function info($title, $descr, $block = false, $color = '#2a2') {
        self::inputLog($title, $descr, $block, $color);
    }

    /**
     * Вывод ошибок, не влияющих на работу
     */
    public static function notice($text, $block = true) {
        $type = self::$_types['notice'];
        //self::inputLog('Notice', $text, $block, '#FB0', 'error');
    }

    /**
     * Вывод серъезных ошибок
     */
    public static function warning($text, $block = true) {
        $b=debug_backtrace();
        $text .= "\n" . $b[1]['file'] . ':' . $b[1]['line'];
        self::inputLog('Warning', $text, $block, 'red', 'error');
        RegistryContext::instance()->set('error', self::viewLog());
        echo self::viewLog();
        die();
    }

    protected  static function inputLog($title, $descr, $block = true, $color = '#ff0' , $filename = 'info') {
        if (self::$_screen) {
            self::$aLog[++self::$i] = TFormat::htmlMessageLog($title, $descr, $block, $color);
        }
        if (self::$_file) {
            $filename = Helper::logFileWrite($filename, TFormat::txtMessageLog($title, $descr));
        }
      //  echo self::$aLog[self::$i];
        //return  self::$aLog[self::$i];
    }

    public static function viewLog() {
        $html = '';
        foreach (self::$aLog as $key => $value) {
            $html .= str_pad($key, 1, '.', STR_PAD_LEFT) .' - '. $value;
        }
        return $html;
       
    }

}