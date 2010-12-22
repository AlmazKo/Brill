<?php
/**
 * Daemon
 * Родительский класс всех демонов, пауков, ботов и прочей нечисти
 */
abstract class Daemon {
    /* ПЛАНИРУЕМЫЕ
     * инициализация
     * просмотр списка демонов
     * просмотр списка запущенных демонов
     * запуск демона
     * проверка что запуще из консоли
     * получение параметров запуска
     * остановка демона
     * получить статус демона
     * получить информацию о демоне ( сколько щас жрет памяти, процессора, сколько уже работает)
     * apaчевский аналог start|stop|restart|reload|force-reload|start-htcacheclean|stop-htcacheclean|status
     *
     */

    protected
    // ссылка на конфигурацию модуля
        $_module;
    public function  __construct() {}
    public function start() {}
    public function stop() {}
    
    public function restart() {}
    public function isRun() {}
    public function getStatus() {}
    protected function _setStatus() {}
    protected function _error() {}

    public function setModule($module) {
        $this->_module = $module;
    }


    public function setParams($params) {
        $this->_params = $params;
    }
    /**
     * Сколько копий дмено можно запусать одновременно
     */
    public function maxCountRun(){
        return 4;
    }
}