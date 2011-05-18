<?php
/**
 * Description of se_GoogleStrategy
 *
 * @author almaz
 */
class GoogleStrategy implements ParsingStrategy {
    const URL_SEARCH = 'http://www.google.ru/search';
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
    }
    
    protected function _getInterface() {
        $this->count++;
        $this->totalCount++;
        $this->curl->resetCookies();
        $this->sleep = 100000;
        $interface = st_Lib::getInterface(st_Lib::INTERFACE_GOOGLE);
        if (!st_Lib::isLocal($interface['interface'])) {
            $this->curl->setOpt(CURLOPT_INTERFACE, $interface['interface']);
        }
        
        
        return $interface['interface'];
    }
    
    public function parse(Keyword $keyword, $countKeywords = 10) {
        $this->_getInterface();
        $this->sleep = 100000; 
        $attemts = 0;
        $customPage = 0;
        $urls = array();
      //  var_dump($keyword);
        while(count($urls) < $countKeywords) {
            echo "\n Собрано: " . count($urls). "\n";
            try {
                $currentUrls = $this->_parse($keyword, $countKeywords, $customPage);
            } catch (GoogleException $ge) {
                $this->curl->resetCookies();
                $this->errors++;
                echo "\n --->>-- ОШИБКА".$this->errors . "\n";
                if ($this->errors> 7) {
                    $this->errors = 0;
                    throw new LimitInterfacesException('много ошибок.');
                }
                $this->_getInterface();
                $currentUrls = $ge->getUrls();
                if (++$attemts > 1) {
                    $urls = array_merge($currentUrls, $urls);
                   // $this->sleep += rand (100000,200000); 
                    throw new GoogleException($urls, $ge->getCurrentPage(), $message = 'Google error.');
                }
              #  $this->sleep += 1500000 * pow(2, $attemts); 
               // $this->sleep += rand(1000000, 1500000); 
                $customPage = $ge->getCurrentPage();
            }
            $urls = array_merge($currentUrls, $urls);
        }
        return $urls;
    }
    
    protected function _parse($keyword, $countKeywords = 10, $customPage = 0) {
    $urls = array();
        $depth = $customPage + (int) ceil($countKeywords / 10);
        for ($page = $customPage; $page < $depth; $page++) {
            echo "\n Задержка: ".(float)$this->sleep/1000 . "\n";
             usleep($this->sleep);
            $this->curl->setGet(array(
                 'hl'       => 'ru', 
                 'q'        => rawurlencode($keyword),
                 'start'    => $page * 10
            ));
                $this->sleep += rand(35000, 51000); 
            $response = $this->curl->requestGet(self::URL_SEARCH)->getResponseBody();
//            if (!rand(0,11)) {
//                $response = 'asda<>asadasdmaslcmwlu e<><<M>>S><A><S>AS<S><DMLI@DM@>D>@<S<';
//            } else {
//                $response = file_get_contents(MODULES_PATH. 'SEParsing/Daemons/Mocks/googleAnswer.html');
//            }
            
//            $response = file_get_contents(MODULES_PATH. 'SEParsing/Daemons/Mocks/googleAnswer_1.html');
//            libxml_use_internal_errors(true);
//
//            $DOM = new DOMDocument;
//            $DOM->loadHTML($response);
//            libxml_use_internal_errors(false);
//            var_dump($DOM->saveXML());
//            die;
            
            
            preg_match_all ('~<h3 class="r"><a href="(.*?)" (.*?)</h3>~', $response, $match);
            if (empty($match[1])) {
                 throw new GoogleException($urls, $page,'Google Error');
            }
            
            
           $urls = array_merge($urls, $match[1]);

           echo "\nнайдено " . count($urls);
        }
        
        return $urls;
        
    }
}