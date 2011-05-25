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
//
//
//
//        $curl = new Curl();
//        $opt = array (CURLOPT_HEADER => true,
//                      CURLOPT_RETURNTRANSFER => true,
//                      CURLOPT_FOLLOWLOCATION => false,
//                      CURLOPT_TIMEOUT => 20,
//                      CURLOPT_CONNECTTIMEOUT => 7,
//                      CURLOPT_FOLLOWLOCATION => 1,
//                      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3"
//                    );
//        
//        $curl->setOptArray($opt);
//  $curl->setProxy('95.169.184.240', '8080', 'webexpert', 'pvofiusyf');
//
//            $curl->setGet(array(
//                 'hl'       => 'ru', 
//                 'q'        => rawurlencode('Окна'),
//                 'start'    => 0 * 10
//            ));
//      
//      $response = $curl->requestGET('http://www.google.ru/search')->getResponseBody();
//      var_dump($response);
//die;
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
            //загрука автолоудера
    }

    /**
     * Запускает экшен
     */
    private function handleRequest() {
        $request = RegistryRequest::instance();
       // ob_start();
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
    //    $content = ob_get_clean();
      //  Helper::logwrite('goog_'.date('Y-m-d'), $content);
      //  echo $content;
        
    }
}