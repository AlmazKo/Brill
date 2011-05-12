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
    protected $curl;
    
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
        $urls = array();
        try {
            
        } catch (GoogleXmlException $ge) {
            
        }
        
        return $urls;
    }
    
    protected function _parse($keyword, $countKeywords = 10, $customPage = 0) {
    $urls = array();
        $depth = $customPage + (int) ceil($countKeywords / 10);
        for ($page = $customPage; $page < $depth; $page++) {
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
