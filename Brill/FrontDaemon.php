<?php
/*
 * фасад для демонов
 */
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
     *
     *
     */
class FrontDeamon {
    static function run() {
        $instance = new Front();
        $instance->init();
        $instance->handleRequest();
    }

    /**
     * Подгрузка модулей и прочего
     *
     */
    private function init() {

    

    }

    /**
     * Запускает экшен
     */
    private function handleRequest() {
        $request = RegistryRequest::instance();
        if ($request->isConsole()) {
            
        } else {
            
            // очень странно будет если ктото сюда попадет
        }
        
//      $context = RegistryContext::instance();
        // фабрика демонов
        $deamonR = new Underworld();
        $deamon = $deamonR->summonDeamon($request);
        $act->start();
    }
}
