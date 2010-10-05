<?php

/**
 *  ParserYandexXml
 *
 * Парсит Yandex.Xml
 * @author almaz
 */
require_once 'Parser.php';
require_once DIR_PATH . '/Models/Keywords.php';
require_once DIR_PATH . '/Models/Sites.php';
require_once DIR_PATH . '/Models/Urls.php';
require_once DIR_PATH . '/Models/Positions.php';
class ParserYandexXml extends Parser{
    protected function  getIp() {
        return $this->db->getIp();
    }

    /**
     * Constructing Xml request for Yandex.ru
     * @param page page
     * @return string
     */
    protected function getXMLRequest($query, $page = 1){
        $data = '<?xml version="1.0" encoding="windows-1251"?>'."\r\n";
        $data .= "<request>\r\n";
        $data .= "<query>$query</query>\r\n";
        $data .= "<page>$page</page>\r\n";
        $data .= "<groupings>\r\n";
        $data .= "<groupby attr=\"d\" mode=\"deep\" groups-on-page=\"100\" docs-in-group=\"1\" />\r\n";
        $data .= "</groupings>\r\n";
        $data .= "</request>\r\n";
        return  $data;
    }

    // cleaning query
    protected function setQuery($query){
        $query=str_replace('&','&amp;', $query);
        $query=str_replace('<','&lt;', $query);
        $query=str_replace('>','&gt;', $query);
        return trim($query);
    }


    // cleaning url for compare
    protected function getHost($url){
        return str_replace ("www.", "",parse_url(strtolower($url), PHP_URL_HOST));
    }

    protected function getPath($url){
        return parse_url ($url, PHP_URL_PATH);
    }

    protected function getGET($region){

        $path = '';
        if (!$region) {
            $region = 213;
        }
        
        $path = "?lr=".$region;
        $url = 'http://xmlsearch.yandex.ru/xmlsearch'.$path;
        return $url;
    }

    /**
     * Parsing Yandex Xml string
     * @param SimpleXMLElement $response response YanderXML
     * @param string $url_comp  url для сравнения
     * @return array (z_Seo, z_Seocomp)
     */
    private function parsed($response){

        $positions = array();
        $pos       = 0;
        $groups    = $response->results->grouping->group;
        $i         = 0;
        $ps;

        
        foreach($groups as $value){
            $pos++;
            $ps['pos'] = $pos;

            $url = (string) $value->doc->url;
            $parsedUrl = @parse_url($url);
            $ps['site'] = $parsedUrl['host'];//
            $ps['url'] = $url;
            $ps['links_search'] = (string) $value->doc->properties->_PassagesType;//найдено по ссылке
            $positions[md5($url)] = $ps;
        }

        return $positions;
    }

    protected function parsing(Keywords $k, $dot = false){

        $query = $this->setQuery($k->name);
        $query .= $dot ? '.' : '';
        $depth = 1;
        $finded = false;
        $pos = array();
        for ($page = 0; $page < $depth; $page++){

            $this->curl_opt[CURLOPT_INTERFACE] = $this->getIp();
            $this->curl_opt[CURLOPT_POSTFIELDS] = 'text='.$this->getXMLRequest($query, $page);
            $xml_response = $this->request();
           
            $attempts = 2;// +2 попытки
            while(empty($xml_response) && $attempts!=0){
                $this->curl_opt[CURLOPT_INTERFACE] = $this->getIp();
                var_dump('Яндекс не ответил, может не понравился ip, поробуем еще раз...');
                sleep(1);
                $xml_response = $this->request();
                $attempts--;
            }

            if(empty($xml_response)){
                var_dump ('Error: Яндекс не ответил на данный запрос');
                return null;
            }

            $yaerror = strstr($xml_response, '<error code=');
            if($yaerror){
                var_dump ('Ошибка на Яндексе: '.$yaerror);
                return null;
            }
            
            $sxe = simplexml_load_string($xml_response)->response;
            $pos += $this->parsed($sxe);
           // Log::dump($pos, 'Black', $k->name);
        }

        return $pos;
    }

    public function run(RegistryParser $config){
        $this->initCurl();
        $keywords = array();
        $this->db = $config->get('db');
        $db = $this->db;
        $keywords = $db->getKeywords(10);

        if(!$keywords) die ();//'Закончились ключевики');
        $this->curl_opt[CURLOPT_POST] = 1;

        foreach ($keywords as $kw){
            
           $this->curl_opt[CURLOPT_URL] = $this->getGET($kw->region_id);
           $ps = $this->parsing ($kw);
           if ($ps) {
               $psDot = $this->parsing ($kw, true);
               foreach ($ps as  $val) {
                    $p = new Positions();
                    $s = new Sites;
                    $u = new Urls;
                    $posDot = 0;
                    $idPos = md5($val['url']);
                    if(isset($psDot[$idPos])) $posDot = $psDot[$idPos]['pos'];
                    $p->keyword_id = $kw->id;
                    if (!$s->getObject('name', $val['site'])) {
                       $s->name = $val['site'];
                       $s->id = $s->add();
                    }
                    if (!$u->getObject('url', $val['url'])) {
                       $u->url = $val['url'];
                       $u->site_id = $s->id;
                       $u->id = $u->add();
                    }
                    $p->site_id = $s->id;
                    $p->url_id = $u->id;

                    $p->pos = $val['pos'];
                    $p->pos_dot = $posDot;

                    $p->links_search = $val['links_search'];
                    $p->add();


               }
               $kw->yandex = 'Сalculated';
           } else {
               $kw->yandex = 'Error';
           }
           
           $kw->save();
           //
        }


       // echo 'Сделано запросов: '.$this->count_request.'; Пропарсено ключевиков: '.$this->db->numberChangedKeys();
    }
}
