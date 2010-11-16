<?php
/**
 * родитель всяких парсеров, всех кому нужен курл
 *
 * @author almaz
 */

abstract class se_Parser extends Daemons
{
    protected $curl;
    protected $curl_opt = array();
    protected $count_request;

    public function  __construct($settings) {
        RunTimer::addTimer('Curl');
    }
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
        $this->curl = curl_init ();
        $this->curl_opt[CURLOPT_HEADER] = false;
        $this->curl_opt[CURLOPT_RETURNTRANSFER] = true;
        $this->curl_opt[CURLOPT_FOLLOWLOCATION] = false;
        $this->curl_opt[CURLOPT_TIMEOUT] = 30;
    }


    final function  __destruct() {
            if($this->curl) curl_close ($this->curl);


    }

    abstract public function start(RegistryParser $config);

}
