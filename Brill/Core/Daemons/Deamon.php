<?php
/**
 * Deamon
 * Родительский класс всех демонов, пауков, ботов и прочей нечисти
 */
class Deamon {
    /*
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
    abstract public function start() {}
    abstract public function stop() {}
    public function restart() {}
    public function isRun() {}
    public function getStatus() {}
    protected function _setStatus() {}
    protected function _error() {}
}