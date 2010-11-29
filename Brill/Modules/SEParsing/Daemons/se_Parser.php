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
        $_countRequests,
        //сколько страниц проходить по поисковой выдаче
        $_depth = 1,
        //сколько ссылок запрашивать на страницу
        $_linksInPages = 20;

    public function  __construct() {
        parent::__construct();
        $this->curl = new Curl();
        $opt = array (CURLOPT_HEADER => true,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_FOLLOWLOCATION => false,
                      CURLOPT_TIMEOUT => 20,
);
        $this->curl->setOptArray($opt);
    }
    /**
     *
     * Посылает запросы и по циклу проходит страницы
     * @param Keyword $k  ключевик из z_keywords
     *
     */
  //  protected function parsing(Keywords $k);

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

    }


    final function  __destruct() {
        if(isset($this->curl)) {
            $this->curl->close();
        }
    }

}
