<?php
/**
 * родитель всяких парсеров, всех кому нужен курл
 *
 * @author almaz
 */

abstract class se_Parser extends Daemon
{
    protected 
        $_curl,
        $_countRequests;

    public function  __construct() {}
    /**
     *
     * Посылает запросы и по циклу проходит страницы
     * @param Keyword $k  ключевик из z_keywords
     *
     */
    abstract protected function parsing(Keywords $k);

    /**
     *
     * Получает ip для парсера
     * @return string IP адрес
     *
     */
    abstract protected function getIp();


    /**
     * Получает строку Get-запроса
     * @return string GET-запрос
     *
     */
    protected function getGET(){}

    /**
     * Получает строку POST-запроса
     * @return string POST-запрос
     *
     */
    protected function getPOST(){
        return null;
    }

    final protected function request(){
        RunTimer::addPoint('Curl');
        curl_setopt_array($this->curl, $this->curl_opt);
        $response = curl_exec ($this->curl);
        RunTimer::endPoint('Curl');
        return $response;
    }

    final protected function initCurl(){
        $this->curl = new Curl();
        $opt[CURLOPT_HEADER] = false;
        $opt[CURLOPT_RETURNTRANSFER] = true;
        $opt[CURLOPT_FOLLOWLOCATION] = false;
        $opt[CURLOPT_TIMEOUT] = 30;
        $this->curl->setOptArray($opt);
    }


    final function  __destruct() {
        if($this->curl) {
            $this->curl->close();
        }
    }

}
