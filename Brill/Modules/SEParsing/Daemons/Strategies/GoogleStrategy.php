<?php
/**
 * Description of se_GoogleStrategy
 *
 * @author almaz
 */
class GoogleStrategy implements ParsingStrategy {
    const URL_SEARCH = 'http://www.google.ru/search';
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
    
    function _parse(Keyword $keyword, $countKeywords = 10) {
        $urls = array();
        $depth = (int) ceil($countKeywords / 10);
        for ($page = 0; $page < $depth; $page++) {
            $this->curl->setGet(array(
                 'hl'       => 'ru', 
                 'q'        => rawurlencode($keyword),
                 'start'    => $page * 10
            ));

           // $response = $this->curl->requestGet(self::URL_SEARCH)->getResponseBody();
            $response = file_get_contents(MODULES_PATH. 'SEParsing/Daemons/Mocks/googleAnswer.html');
            preg_match_all ('~<h3 class="r"><a href="(.*?)" (.*?)</h3>~', $response, $match);
            if (empty($match[1])) {
                 throw new GoogleException('Google Error');
            }
           $urls = array_merge($urls, $match[1]);
        }
        var_dump($urls); die;
        
        return $urls;
    }
}
