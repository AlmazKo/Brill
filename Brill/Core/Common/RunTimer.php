<?php
/*
 * RunTimer
 * Фабрика таймеров, должна быть вызвана, чтобы потом сработал деструктор
 */
include 'Timer.php';

final class RunTimer {
    private $_nameGeneralTimer;
    private static $_listTimers = array();

    public function  __construct($name = 'TOTAL_TIMER') {
        $this->_nameGeneralTimer = $name;
        self::addTimer($this->_nameGeneralTimer);
        self::addPoint($this->_nameGeneralTimer);
    }

    function  __destruct() {
        self::endPoint($this->_nameGeneralTimer);
        $title = 'Timers:';
        $descr = '';
        foreach (self::$_listTimers as $key => $value) {
           $descr .= '"' . $key . '": ' . TFormat::timer($value->getAllTime(true)) . "\n";
       }
       Log::info($title, $descr, true);
    }
    //задать точку для таймера
    public static function addPoint($nameTimer) {
        self::$_listTimers[$nameTimer]->addPoint();
    }
    //возвратить отработанное время от точки до точки
    public static function endPoint($nameTimer){
        return TFormat::timer(self::$_listTimers[$nameTimer]->endPoint());
    }

    /**
     * @param string $name Название нового таймера
     */
    public static function addTimer($name) {
        if (isset(self::$_listTimers[$name])) {
            Log::notice('Таймер ' . $nameTimer . ' уже существует');
            return false;
        } else {
           self::$_listTimers[$name] = new Timer();
            return true;
        }
    }
}
