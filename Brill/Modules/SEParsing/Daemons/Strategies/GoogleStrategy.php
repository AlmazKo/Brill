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
    
    public function parse(Keyword $keyword, $countKeywords = 10) {
        
        $interface = st_Lib::getInterface(st_Lib::INTERFACE_GOOGLE);
        if (st_Lib::INTERFASE_LOCALHOST != $interface['interface']) {
            $this->curl->setOpt(CURLOPT_INTERFACE, $interface['interface']);
        }
        $attemts = 0;
        $customPage = 0;
        $urls = array();
        while(count($urls) == $countKeywords){
            try {
                $currentUrls = $this->_parse($keyword, $countKeywords, $customPage, $interface['interface']);
            } catch (GoogleException $ge) {
                if (++$attemts > 4) {
                   throw new GoogleException($urls, $ge->getCurrentPage(), $message = 'Google error.');
                }
                $this->sleep *= $attemts + 1; 
                $customPage = $ge->getCurrentPage();
                $currentUrls = $ge->getUrls();
                
                
            }
            $urls = array_merge($currentUrls, $match[1]);
        }
        return $urls;
    }
    
    protected function _parse($keyword, $countKeywords = 10, $customPage = 0, $ip) {
    $urls = array();
        $depth = $customPage + (int) ceil($countKeywords / 10);
        for ($page = $customPage; $page < $depth; $page++) {
            usleep($this->sleep);
            $this->curl->setGet(array(
                 'hl'       => 'ru', 
                 'q'        => rawurlencode($keyword),
                 'start'    => $page * 10
            ));

            $response = $this->curl->requestGet(self::URL_SEARCH)->getResponseBody();
          //  $response = file_get_contents(MODULES_PATH. 'SEParsing/Daemons/Mocks/googleAnswer.html');
            preg_match_all ('~<h3 class="r"><a href="(.*?)" (.*?)</h3>~', $response, $match);
            if (empty($match[1])) {
                 throw new GoogleException($urls, $page,'Google Error');
            }
           $urls = array_merge($urls, $match[1]);
        }
        
        return $urls;
        
    }
}
