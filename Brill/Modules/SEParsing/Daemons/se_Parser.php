<?php
/**
 * родитель всяких парсеров, всех кому нужен курл
 *
 * @author almaz
 */

abstract class se_Parser extends Daemon {

    const PARSING_STATUS_OK = 'Ok';
    const PARSING_STATUS_NO = 'No';
    const PARSING_STATUS_BUSY = 'Busy';
    const PARSING_STATUS_ERROR = 'Error';

    protected 
        //сколько страниц проходить по поисковой выдаче
        $_depth = 1,
        //сколько ссылок запрашивать на страницу
        $_linksInPages = 10,
        $_strategy;
    
    public function initialize($options) {
        switch (true) {
            case $this instanceof se_ParserGoogle:
                $this->_strategy = new GoogleStrategy(); 
                $this->_strategy->setGeneratorUserAgents(new UserAgent());
                break;
            
            case $this instanceof se_ParserYandexXml:
                $this->_strategy = new YandexXMLSimpleStrategy(); 
                break;
            
            default:
                if (isset($options['s'])) {
                    $this->_strategy = new $options['s'];
                } 
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
    
    
    /**
     * Задать статус для сета в базе
     * @param int $setId
     * @param string $searchType
     * @param string $status
     * @return bool 
     */
    protected function setStatusParsing($setId, $searchType, $status) {
        return (bool)   DB::execute(se_StmtDaemon::prepare(se_StmtDaemon::SET_USED_SET),
                        array(':set_id' => $setId, ':search_type' => $searchType, ':status' => $status)
                        )->rowCount();
    }

}
