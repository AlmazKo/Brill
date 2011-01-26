<?php
/**
 * Log
 *
 * Класс для отладки кода
 */

class Log {
    const TIMERS = '__timers__';
    const
        LVL_NONE = 0,
        LVL_ONLY_SCREEN = 1,
        LVL_ONLY_FILE = 2,
        LVL_ALL = 4;

    private static
        $_debugLevel = null,
        $_screen = false,
        $_file = false,
        $aLog = array(),
        $_isConsole = false,
        $i = 0;

    /**
     * Задать уровень логирования
     * @param int $num
     */
    public static function setLevel($num, $isConsole = false) {
        if (null === self::$_debugLevel) {
            self::$_isConsole = $isConsole;
            switch($num) {
                case self::LVL_ONLY_SCREEN:
                    self::$_screen = true;
                    break;
                case self::LVL_ONLY_FILE:
                    self::$_file = true;
                    break;
                case self::LVL_ALL:
                    self::$_screen = true;
                    self::$_file = true;
                    break;
                default:
                    self::$_debugLevel = false;
            }
        }
    }

    /**
     * Вывод дампа чего либо
     * @param mixed $obj
     * @param string $color
     */
    public static function dump($obj, $color = '#00a0ff', $title = 'Dump') {
        if (self::$_screen) {
            $b = debug_backtrace();//$text = "\n" . $b[1]['file'] . ':' . $b[1]['line'];
            ob_start();
            var_export($obj);
            $descr = ob_get_clean();
            $descr = TFormat::highlight($descr);
            echo TFormat::htmlMessageLog($title, $descr, true, $color);
        }
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
    //    $type = self::$_types['notice'];
        //self::inputLog('Notice', $text, $block, '#FB0', 'error');
    }

    /**
     * Вывод серъезных ошибок
     */
    public static function warning($text, $block = true, $title = 'Warning') {
        $b = debug_backtrace();
        for ($i = 1; $i < 4; $i++) {
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
            if (self::$_isConsole) {
                return TFormat::txtMessageLog($title, $descr);
            }
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
     
     public static function isView() {
         return self::$_screen;
     }
}