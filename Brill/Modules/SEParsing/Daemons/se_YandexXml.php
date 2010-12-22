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
        ATTEMPTS = 0;

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
        $this->curl->setPrepared(false);
     }

    protected function _configure() {
    }

    /**
     * ПОлучить интерфейс для запроса
     * @return array
     */
    protected function getInterface() {
       return st_Lib::getInterface(st_Lib::INTERFACE_YA_XAML);
    }

    /**
     * Constructing Xml request for Yandex.ru
     * @param page page
     * @return string
     */
    final protected function getXMLRequest($query, $page = 0){
        $data = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" .
                "<request>\r\n" .
                "<query>".Xml::prepareTextForXml($query)."</query>\r\n" .
                "<page>$page</page>\r\n" .
                "<groupings>\r\n" .
                '<groupby attr="d" mode="flat" groups-on-page="' . $this->_linksInPages .'" docs-in-group="1" />' . "\r\n" .
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

    final protected function requestYandex($xmlQuery) {
        $interface = $this->getInterface();
        if (st_Lib::INTERFASE_LOCALHOST != $interface['interface']) {
            $this->curl->setOpt(CURLOPT_INTERFACE, $interface);
        }
        $this->curl->setGet(array('user' => $interface['login'], 'key'  => $interface['xml_key']));
        Log::dump($interface);
        $this->curl->setPost(array('text' => $xmlQuery));
        $response = $this->curl->requestPost(self::URL_YA_SEARCH)->getResponseBody();

        $attempts = self::ATTEMPTS;
        while (self::ATTEMPTS && !$response && $attempts) {
            //делаем попытку еще раз
            $interface = $this->getInterface();
            if (st_Lib::INTERFASE_LOCALHOST != $interface['interface']) {
                $this->curl->setOpt(CURLOPT_INTERFACE, $interface);
            }
            $this->curl->setGet(array('user' => $interface['login'], 'key'  => $interface['xml_key']));
 
            $response = $this->curl->requestPost(self::URL_YA_SEARCH)->getResponseBody();
            $attempts--;
            sleep(1);
        }

        if(self::ATTEMPTS && !$attempts){
            LogInDb::notice($this, 'Яндекс не ответил на данный запрос. Было сделано '.self::ATTEMPTS.' дополнительных попыток');
            return;
        }

        $sxe = simplexml_load_string($response)->response;
        if ($sxe->error) {
            LogInDb::notice($this, $sxe->error);
            die();
        }
        return $sxe;
    }

}