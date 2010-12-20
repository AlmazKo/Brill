<?php
/**
 * Log
 *
 * Класс для отладки кода
 */

class Log {
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
        $b = debug_backtrace();//$text = "\n" . $b[1]['file'] . ':' . $b[1]['line'];
        ob_start();
        var_dump($obj);
        $descr = ob_get_clean ();
        $descr = TFormat::highlight($descr);
        echo self::inputLog($title, $descr, true, $color);
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
    public static function warning($text, $block = true, $title = 'Warning') {
        $b = debug_backtrace();
        for ($i = 1; $i < 3; $i++) {
               $text .= "\n$i:" . $b[$i]['file'] . ':' . $b[$i]['line'];
        }

        echo self::inputLog($title, $text, true, 'red', 'error');
       ## TODO  должна обрабатываться или та строка или нижняя
       //  RegistryContext::instance()->set('error', self::viewLog());
        echo self::viewLog();
        die();
    }

    protected  static function inputLog($title, $descr, $block = true, $color = '#ff0' , $filename = 'info') {
        if (self::$_file) {
            $filename = Helper::logFileWrite($filename, TFormat::txtMessageLog($title, $descr));
        }
        if (self::$_screen) {
            if (!$block) {
                self::$aLog[++self::$i] = TFormat::htmlMessageLog($title, $descr, false, $color);
            } else {
                self::$aLog[++self::$i] = TFormat::htmlMessageLog($title, $descr, false, $color);
                return TFormat::htmlMessageLog($title, $descr, $block, $color);
            }
        }
        if (self::$i) {
            return  self::$aLog[self::$i];
        } else {
            return '';
        }
        
    }

    /**
     * Возврщает все логи в html
     * @return string
     */
    public static function viewLog() {
        RunTimer::destruct();
        $html = '';
        foreach (self::$aLog as $key => $value) {
            $html .= str_pad($key, 1, '.', STR_PAD_LEFT) .' - '. $value;
        }
        return $html;
     }
}