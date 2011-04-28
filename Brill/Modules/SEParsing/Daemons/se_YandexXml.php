<?php

/**
 *  Абстрактный класс для работы с ЯНдекс.XML
 *
 * @author almaz
 */
abstract class se_YandexXml extends se_Parser {
    const
        //url yandex XML
        URL_YA_SEARCH = 'http://xmlsearch.yandex.ru/xmlsearch',

        //количество попыток, производимых при неудачном ответе
        ATTEMPTS = 0,

        /*
         * количество страниц, запрашиваемых у яндекса, на одной странице.
         * диапозон значений: [20,...,100]
         */
        LINKS_IN_PAGE = 100,
            
        //Максимальное количество страниц отдаваемое яндексом;
        MAX_LINKS_YA = 1000;

        const DEFAULT_REGION_ID = 213;
    protected
        $_lnk2;

    protected
        $_depth = 1,
        $_linksInPages,
            
        /*
         * глубина запросов, вичисляется в завимости от $_linksInPages.
         * дипазон: [1,...,50]
         */
        $_countPages;

    public function  __construct() {
        parent::__construct();
        RegistryDb::instance()->createConnect(array('localhost', 'root', '12345', 'brill'));
        DB::connect();
        $this->curl->setResponseCharset(ENCODING_CODE, true);
        $this->curl->setPrepared(false);
        $this->curl->setGet(array('lr' => 213));

        if (!$this->_linksInPages) {
            $this->_linksInPages = self::LINKS_IN_PAGE;
        }
        $this->_countPages = ceil(self::MAX_LINKS_YA / $this->_linksInPages - 0.1);
     }

    protected function _configure() {
    }

    /**
     * ПОлучить интерфейс для запроса
     * @return array
     */
    protected function getInterface() {
       $interface = st_Lib::getInterface(st_Lib::INTERFACE_YA_XAML);
       echo "\nПолучили ip: " . $interface['interface'];
       return $interface;
    }

    /**
     * Constructing Xml request for Yandex.ru
     * @param page page
     * @return string
     */
    final protected function getXMLRequest($query, $linksInPage = self::LINKS_IN_PAGE, $page = 0){
        $data = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" .
                "<request>\r\n" .
                '<query>' . Xml::prepareTextForXml($query) . "</query>\r\n" .
                "<page>$page</page>\r\n" .
                "<groupings>\r\n" .
                '<groupby attr="d" mode="deep" groups-on-page="' . $this->_linksInPages .'"/>' . "\r\n" .
                "</groupings>\r\n".
                "</request>\r\n";
        return  $data;
    }

    // cleaning url for compare
    protected function getHost($url){
        return str_replace ('www.', '', parse_url(strtolower($url), PHP_URL_HOST));
    }

    protected function getPath($url){
        return parse_url ($url, PHP_URL_PATH);
    }

    final protected function requestYandex($xmlQuery) {
        $interface = $this->getInterface();
        if (st_Lib::INTERFASE_LOCALHOST != $interface['interface']) {
            $this->curl->setOpt(CURLOPT_INTERFACE, $interface['interface']);
        }
        $this->curl->setGet(array('user' => $interface['login'], 'key'  => $interface['xml_key']));

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
            usleep(500000);
        }

        if(self::ATTEMPTS && !$attempts){
            throw new YandexXmlException('Яндекс не ответил на данный запрос. Было сделано '.self::ATTEMPTS.' дополнительных попыток');
        }
        $sxe = simplexml_load_string($response)->response;
        if ($sxe->error) {
            throw new YandexXmlException($sxe->error);
        }
        return $sxe;
    }

}
