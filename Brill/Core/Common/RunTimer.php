<?php
/*
 * RunTimer
 * Фабрика таймеров, должна быть вызвана, чтобы потом сработал деструктор
 */

final class RunTimer {
    private static $_nameGeneralTimer;
    private static $_listTimers = array();

    public function  __construct($name = 'TOTAL_TIMER') {
        self::$_nameGeneralTimer = $name;
        self::addTimer(self::$_nameGeneralTimer);
        self::addPoint(self::$_nameGeneralTimer);
    }

    function  __destruct() {
       # self::destruct();
    }
    
    //задать точку для таймера
    public static function addPoint($nameTimer) {
        self::$_listTimers[$nameTimer]->addPoint();
    }

    //возвратить отработанное время от точки до точки
    public static function endPoint($nameTimer){
       // return TFormat::timer(self::$_listTimers[$nameTimer]->endPoint());
        return self::$_listTimers[$nameTimer]->endPoint();
    }

    /**
     * @param string $name Название нового таймера
     */
    public static function addTimer($name) {
        if (isset(self::$_listTimers[$name])) {
            Log::notice('Таймер ' . $name . ' уже существует');
            return false;
        } else {
           self::$_listTimers[$name] = new Timer();
            return true;
        }
    }

    /**
     * Останавливает все запущенные таймеры
     * @return array - результат
     */
    public static function destruct() {
        self::endPoint(self::$_nameGeneralTimer);
        $timers = array();
        foreach (self::$_listTimers as $key => $value) {
           ##$descr .= '"' . $key . '": ' . TFormat::timer($value->getAllTime(true)) . "\n";
            $timers[$key] = TFormat::timer($value->getAllTime(true));
            Log::info($key, $timers[$key], true);
        }
        return $timers;
    }
}
