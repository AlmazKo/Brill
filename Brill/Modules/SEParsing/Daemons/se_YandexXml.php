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
//        RegistryDb::instance()->createConnect(array('localhost', 'root', '12345', 'brill'));
//        DB::connect();

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

    // cleaning url for compare
    protected function getHost($url){
        return str_replace ('www.', '', parse_url(strtolower($url), PHP_URL_HOST));
    }

    protected function getPath($url){
        return parse_url ($url, PHP_URL_PATH);
    }



}
