<?php
/**
 * Description of se_GoogleStrategy
 *
 * @author almaz
 */
class YandexXMLSimpleStrategy implements ParsingStrategy {
    const
        CONF_NULL = 0,
        CONF_WITH_DOT = 1,
        URL_SEARCH = 'http://xmlsearch.yandex.ru/xmlsearch',
        //количество попыток, производимых при неудачном ответе
        ATTEMPTS = 0,

        /*
         * количество страниц, запрашиваемых у яндекса, на одной странице.
         * диапозон значений: [20,...,100]
         */
        LINKS_IN_PAGE = 100,
            
        //Максимальное количество страниц отдаваемое яндексом;
        MAX_LINKS_YA = 1000;

    protected $curl;
    protected $attempts = 0;
    protected $sleep = 100000;
    /*
     * счетчики запусков
     */
    protected $totalCount = 0;
    protected $count = 0;

    protected $errors = 0;
    public function __construct() {
        $this->curl = new Curl();
        
        $opt = array (CURLOPT_HEADER => true,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_FOLLOWLOCATION => false,
                      CURLOPT_TIMEOUT => 20,
                      CURLOPT_CONNECTTIMEOUT => 7,
                      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3"
                    );
        
        $this->curl->setOptArray($opt);
        $this->curl->setResponseCharset(ENCODING_CODE, true);
        $this->curl->setPrepared(false);
        $this->curl->setGet(array('lr' => 213));
    }
    
    protected function _getInterface() {
        $this->count++;
        $this->totalCount++;
        $this->curl->resetCookies();
        $this->sleep = 100000;
        $interface = st_Lib::getInterface(st_Lib::INTERFACE_YA_XAML);
        if (!st_Lib::isLocal($interface['interface'])) {
            $this->curl->setOpt(CURLOPT_INTERFACE, $interface['interface']);
        }
        
        
        return $interface['interface'];
    }
    
    public function parse(Keyword $keyword, $countKeywords = 10) {
#         $query = $keyword . ((self::CONF_WITH_DOT == $conf) ? '.' : '');
        $query = $keyword;
        
        $this->curl->setGet(array('lr' => $keyword->region));
        $xmlQuery = $this->getXMLRequest($query);
        $xmlResponse = $this->requestYandex($xmlQuery);
        return $xmlResponse;
    }
    
    /**
     * Constructing Xml request for Yandex.ru
     * @param page page
     * @return string
     */
    protected function getXMLRequest($query, $linksInPage = self::LINKS_IN_PAGE, $page = 0){
        $data = '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" .
                "<request>\r\n" .
                '<query>' . Xml::prepareTextForXml($query) . "</query>\r\n" .
                "<page>$page</page>\r\n" .
                "<groupings>\r\n" .
                '<groupby attr="d" mode="deep" groups-on-page="' . $linksInPage .'"/>' . "\r\n" .
                "</groupings>\r\n".
                "</request>\r\n";
        return  $data;
    }
    
    final protected function requestYandex($xmlQuery) {
        $interface = $this->_getInterface();
        if (st_Lib::INTERFASE_LOCALHOST != $interface['interface']) {
            $this->curl->setOpt(CURLOPT_INTERFACE, $interface['interface']);
        }
        $this->curl->setGet(array('user' => $interface['login'], 'key'  => $interface['xml_key']));

        $this->curl->setPost(array('text' => $xmlQuery));
        $response = $this->curl->requestPost(self::URL_SEARCH)->getResponseBody();

        $attempts = self::ATTEMPTS;
        while (self::ATTEMPTS && !$response && $attempts) {
            //делаем попытку еще раз
            $interface = $this->_getInterface();
            if (st_Lib::INTERFASE_LOCALHOST != $interface['interface']) {
                $this->curl->setOpt(CURLOPT_INTERFACE, $interface);
            }
            $this->curl->setGet(array('user' => $interface['login'], 'key'  => $interface['xml_key']));
 
            $response = $this->curl->requestPost(self::URL_SEARCH)->getResponseBody();
            $attempts--;
//            usleep(500000);
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