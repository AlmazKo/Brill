<?php

/**
 *  ParserYandexXml
 *
 * Парсит Yandex.Xml
 * @author almaz
 */
class se_ParserYandexXml extends se_Parser {
    const
        TBL_IP = 'z_routeip',
        //url yandex XML
        URL_YA_SEARCH = 'http://xmlsearch.yandex.ru/xmlsearch',
        CONF_NULL = 0,
        CONF_WITH_DOT = 1,
        //сколько попыток сделать один запрос
        ATTEMPTS = 2;

    protected
        $_lnk2;

    protected
        $_depth = 1,
        $_linksInPages = 100;

    public function  __construct() {
        parent::__construct();
        RegistryDb::instance()->setSettings(DB::DEFAULT_LNK, array('localhost', 'root', '12345', 'brill'));
        DB::connect();
        //RegistryDb::instance()->setSettings('account', array('localhost', 'webexpert_acc', '3k8GnrcM', 'webexpert_acc'));
        RegistryDb::instance()->setSettings('account', array('localhost', 'root', '12345', 'brill'));
        $this->_lnk2 = DB::connect('account');
     }

    protected function _configure() {
        require_once $this->_module->pathModels . 'sep_Keywords.php';
        require_once $this->_module->pathModels . 'sep_Sites.php';
        require_once $this->_module->pathModels . 'sep_Thematics.php';
        require_once $this->_module->pathModels . 'sep_Sets.php';
        require_once $this->_module->pathModels . 'sep_Regions.php';
        require_once $this->_module->pathModels . 'sep_UrlKeywords.php';
        require_once $this->_module->pathModels . 'sep_Positions.php';
        require_once $this->_module->pathModels . 'sep_Urls.php';

    }

    /**
     * Получить Ip
     * @return string
     */
    protected function  _getIp() {
        $sql = Stmt::prepare(se_StmtDaemon::GET_IP, null);
        $result = DBExt::selectToArray($sql, $this->_lnk2);
        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            Db::query('UPDATE ' . se_Tbl::IP . ' SET ri_quota=ri_quota - 1 WHERE ri_id = ' . $row['ri_id'], self::$lnk2);
            return $row['ri_ip'];
        } else {
            Log::warning('Закончились IP');
        }
    }

    /**
     * Constructing Xml request for Yandex.ru
     * @param page page
     * @return string
     */
    protected function getXMLRequest($query, $page = 1){
        $data = '<?xml version="1.0" encoding="windows-1251"?>' . "\r\n" .
                "<request>\r\n" .
                "<query>$query</query>\r\n" .
                "<page>$page</page>\r\n" .
                "<groupings>\r\n" .
                '<groupby attr="d" mode="deep" groups-on-page="' . $this->_linksInPages .'" docs-in-group="1" />' . "\r\n" .
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

    /**
     * Parsing Yandex Xml string
     * @param SimpleXMLElement $response response YanderXML
     * @param string $url_comp  url для сравнения
     * @return array (z_Seo, z_Seocomp)
     */
    private function parsingXml($response){

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

    /**
     *
     * @param sep_Keywords $k
     * @param int $dot тип парсинга
     * @return <type>
     */
    protected function parsing(sep_Keywords $k, $conf = 0) {

        $query = $k->name . ((self::CONF_WITH_DOT == $conf) ? '.' : '');
        $query = Xml::prepareTextForXml($query);
        $finded = false;
        $pos = array();
        for ($page = 0; $page < $this->_depth; $page++){
         ##   $this->curl->setOpt(CURLOPT_INTERFACE, $this->_getIp());
            $this->curl->setPost(array('text' => $this->getXMLRequest($query, $page)));
            $xml_response = $this->curl->requestPost(self::URL_YA_SEARCH)->getResponseBody();
            $attempts = self::ATTEMPTS;
            while(empty($xml_response) && $attempts!=0){
          ##      $this->curl->setOpt(CURLOPT_INTERFACE, $this->_getIp());
                Log::dump('Яндекс не ответил, попробуем еще раз...');
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
            $pos += $this->parsingXml($sxe);
        }
        return $pos;
    }

    /**
     * Старт бота
     *
     * @param int $type - типа парсинга
     */
    public function start($type = self::CONF_WITH_DOT) {
        $this->_configure();
        parent::start();

        $sql = Stmt::prepare(se_StmtDaemon::GET_KEYWORDS, array('limit' => 1));
        $keywords = Model::getObjectsFromSql('sep_Keywords', $sql);
        if (!$keywords) {
            die ();//'Закончились ключевики');
        }
        $this->curl->setGet(array('user' => 'alexandersuslov',
                                  'key'  => '03.16530698:0be40b9b36e8180e342b869c26086871'));

        if (self::CONF_WITH_DOT == $type) {
            $this->_parseDot($keywords);
        } else if (!$type) {
            $this->_parseSimple($keywords);
        }

       // echo 'Сделано запросов: '.$this->count_request.'; Пропарсено ключевиков: '.$this->db->numberChangedKeys();
    }

    /**
     * Стандартный парсинг
     * @param array $keywords
     */
    private function _parseSimple($keywords) {

    }

    /**
     * Парсинг с "точкой"
     * @param array $keywords
     */
    private function _parseDot($keywords) {
        foreach ($keywords as $kw){
           $this->curl->setGet(array('lr' => $kw->region_id));
           $psDot = $this->parsing ($kw, self::CONF_WITH_DOT);
           if ($psDot) {
               $ps = $this->parsing ($kw);
               $p = new sep_Positions();
               $site = new sep_Sites();
               $url = new sep_Urls();
               foreach ($psDot as $val) {
                    $pos = 0;
                    $idPosDot = md5($val['url']);
                    if (isset($ps[$idPosDot])) {
                        $pos = $ps[$idPosDot]['pos'];
                    }
                    $p->keyword_id = $kw->id;
                    // есть ли такой сайт в базе
                    if (!$site->getObjectField('name', $val['site'])) {
                       $site->name = $val['site'];
                       $site->save();
                    }

                    // есть ли такой url в базе
                    if (!$url->getObjectField('url', $val['url'])) {
                       $url->url = $val['url'];
                       $url->site_id = $site->id;
                       $url->save();
                    }
                    $p->site_id = $site->id;
                    $p->url_id = $url->id;

                    $p->pos_dot = $val['pos'];
                    $p->pos = $pos;

                    $p->links_search = $val['links_search'];
                    $p->saveInBuffer()->reset();
                    $url->reset();
                    $site->reset();
               }
               $p->executeBuffer();
               $kw->yandex = 'Сalculated';
           } else {
               $kw->yandex = 'Error';
           }
           $kw->save();
        }
    }
}