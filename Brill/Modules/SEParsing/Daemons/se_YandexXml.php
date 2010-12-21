<?php

/**
 *  Класс для работы с ЯНдекс.XML
 *
 * Парсит Yandex.Xml
 * @author almaz
 */
abstract class se_YandexXml extends se_Parser {
    const
        TBL_IP = 'z_routeip',
        //url yandex XML
        URL_YA_SEARCH = 'http://xmlsearch.yandex.ru/xmlsearch',
        //сколько попыток сделать один запрос
        ATTEMPTS = 2;

    protected
        $_lnk2;

    protected
        $_depth = 1,
        $_linksInPages = 100;

    public function  __construct() {
        parent::__construct();
        RegistryDb::instance()->setSettings(DB::DEFAULT_LNK, array('localhost', 'root', '12345', 'brill'));
        DB::connect();
        $this->curl->setResponseCharset(ENCODING_CODE, true);
     }

    protected function _configure() {
    }

    protected function getInterface() {
        
    }

    /**
     * Constructing Xml request for Yandex.ru
     * @param page page
     * @return string
     */
    protected function getXMLRequest($query, $page = 1){
        $data = '<?xml version="1.0" encoding="windows-1251"?>' . "\r\n" .
                "<request>\r\n" .
                "<query>$query</query>\r\n" .
                "<page>$page</page>\r\n" .
                "<groupings>\r\n" .
                '<groupby attr="d" mode="deep" groups-on-page="' . $this->_linksInPages .'" docs-in-group="1" />' . "\r\n" .
                "</groupings>\r\n".
                "</request>\r\n";
        return  $data;
    }

    // cleaning url for compare
    protected function getHost($url){
        return str_replace ("www.", "", parse_url(strtolower($url), PHP_URL_HOST));
    }

    protected function getPath($url){
        return parse_url ($url, PHP_URL_PATH);
    }

    protected function requestYandex($xmlQuery) {
        $query = Xml::prepareTextForXml($xmlQuery);
        $interface = $this->getInterface();
        if ('127.0.0.1' != $interface && 'localhost' != strtolower($interface)) {
            $this->curl->setOpt(CURLOPT_INTERFACE, $interface);
        }
        $this->curl->setPost(array('text' => $this->getXMLRequest($query, $page)));
        $response = $this->curl->requestPost(self::URL_YA_SEARCH)->getResponseBody();
        Log::dump($response);
        $attempts = self::ATTEMPTS;
        while(!$response && $attempts!=0){
            $interface = $this->getInterface();
            if ('127.0.0.1' != $interface && 'localhost' != strtolower($interface)) {
                $this->curl->setOpt(CURLOPT_INTERFACE, $interface);
            }
            LogInDb::notice($this, 'Яндекс не ответил...');
            Log::dump('Яндекс не ответил...');
            sleep(1);
            $response = $this->curl->requestPost(self::URL_YA_SEARCH)->getResponseBody();
            $attempts--;
        }

        if(!$attempts){
            LogInDb::notice($this, 'Error: Яндекс не ответил на данный запрос');
            return;
        }

        $yaError = strstr($response, '<error code=');
        if ($yaError) {
            LogInDb::notice($this, 'Яндекс не ответил...'.$response);
            var_dump ('Ошибка на Яндексе: '.$yaerror);
            return;
        }

        $sxe = simplexml_load_string($xml_response)->response;
        if ($sxe->error) {
            LogInDb::notice($this, $sxe->error);
            die();
        }
        return $sxe;
    }

}