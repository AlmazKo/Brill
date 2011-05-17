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
        while(count($urls) != $countKeywords) {
            try {
                $currentUrls = $this->_parse($keyword, $countKeywords, $customPage);
            } catch (GoogleException $ge) {
                $this->_getInterface();
                $currentUrls = $ge->getUrls();
                if (++$attemts > 1) {
                    $urls = array_merge($currentUrls, $urls);
                    $this->sleep = 500000; 
                    throw new GoogleException($urls, $ge->getCurrentPage(), $message = 'Google error.');
                }
              #  $this->sleep += 1500000 * pow(2, $attemts); 
                $this->sleep += 1500000; 
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
            usleep($this->sleep);
            $this->curl->setGet(array(
                 'hl'       => 'ru', 
                 'q'        => rawurlencode($keyword),
                 'start'    => $page * 10
            ));

          //  $response = $this->curl->requestGet(self::URL_SEARCH)->getResponseBody();
            if (!rand(0,1105)) {
                $response = 'asda<>asadasdmaslcmwlu e<><<M>>S><A><S>AS<S><DMLI@DM@>D>@<S<';
            } else {
                $response = file_get_contents(MODULES_PATH. 'SEParsing/Daemons/Mocks/googleAnswer.html');
            }
            
            
            preg_match_all ('~<h3 class="r"><a href="(.*?)" (.*?)</h3>~', $response, $match);
            if (empty($match[1])) {
                 throw new GoogleException($urls, $page,'Google Error');
            }
           $urls = array_merge($urls, $match[1]);
        }
        
        return $urls;
        
    }
}
