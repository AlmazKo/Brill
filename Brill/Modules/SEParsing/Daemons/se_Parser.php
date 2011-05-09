<?php
/**
 * родитель всяких парсеров, всех кому нужен курл
 *
 * @author almaz
 */

abstract class se_Parser extends Daemon {
    protected 
        //сколько страниц проходить по поисковой выдаче
        $_depth = 1,
        //сколько ссылок запрашивать на страницу
        $_linksInPages = 10,
        $_strategy;
    
    public function initialize($options) {
        if (isset($options['s'])) {
            $this->_strategy = new $options['s'];
        } else {
            $this->_strategy = new GoogleStrategy();
        }
        $this->options = $options;
    }

    public function  __construct() {
   //     parent::__construct();
        unset(self::$_cliParams[Daemon::KEY_NAME_DAEMON]);
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
     //   RunTimer::addPoint('Curl');
        curl_setopt_array($this->curl, $this->curl_opt);
        $response = curl_exec ($this->curl);
    //    RunTimer::endPoint('Curl');
        return $response;
    }

    final protected function initCurl(){

    }

    public function  __destruct() {
    }

}
