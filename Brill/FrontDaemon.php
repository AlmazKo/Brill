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


//Framework Initilization
require 'Core/InitDaemon.php';

class FrontDeamon {
    static function run() {
        $instance = new self();
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
      //  ob_start();
        if ($request->isConsole()) {
            try {
                $daemonR = new Underworld();
                $daemon = $daemonR->summon();
                if (Underworld::countRunningDaemons($daemon) <= $daemon->maxCountRun()) {
                     $daemon->start();
                }
            } catch (CliInput $input) {
                 Log::info($input->getMessage());
            } catch (Warning $e) {
                $log = LogMysql::getLog();
                foreach ($log as $key => $value) {
                    $log[] = $value[1];
                    unset($log[$key]);
                }
              //  var_export($log);
                Log::warning($e->getMessage());
            }
        }
      //  $content = ob_get_clean();
     //   Helper::logwrite('ya_'.date('Y-m-d'), $content);
      //  echo $content;
        
    }
}