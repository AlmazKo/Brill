<?php

/**
 *  ParserYandexXml
 *
 * Парсит Yandex.Xml
 * @author almaz
 */
class se_ParserYandexXml extends se_YandexXml {
    const
        CONF_NULL = 0,
        CONF_WITH_DOT = 1;

    protected
        $_lnk2;

    protected
        $_depth = 1,
        //сколько позиций брать за раз
        $_linksInPages = 10;

    public function  __construct() {
        parent::__construct();
        self::$_cliParams += array('-type' => 'Type 1|0. С точкой или без');
     }

    /**
     * Подключение всех выжных моделей
     */
    protected function _configure() {
        require_once $this->_module->pathModels . 'sep_Keywords.php';
        require_once $this->_module->pathModels . 'sep_Sites.php';
        require_once $this->_module->pathModels . 'sep_Thematics.php';
        require_once $this->_module->pathModels . 'sep_Sets.php';
        require_once $this->_module->pathModels . 'sep_Regions.php';
        require_once $this->_module->pathModels . 'sep_Positions.php';
        require_once $this->_module->pathModels . 'sep_Urls.php';
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
        $ps = array();

        foreach ($groups as $value) {
            $pos++;
            $url = (string) $value->doc->url;
            $parsedUrl = @parse_url($url);
            $ps[$pos]['site'] = $parsedUrl['host'];//
            $ps[$pos]['url'] = $url;
            $ps[$pos]['links_search'] = (string) $value->doc->properties->_PassagesType;//найдено по ссылке
            $positions[md5($url)] = $ps;
        }
        return $ps;
    }

    /**
     *
     * @param sep_Keywords $k
     * @param int $dot тип парсинга
     * @return array
     */
    protected function parsing($k, $conf = 0) {
        $query = $k['name'] . ((self::CONF_WITH_DOT == $conf) ? '.' : '');
     //   Log::dump($query);
        $xmlQuery = $this->getXMLRequest($query);
        $xmlResponse = $this->requestYandex($xmlQuery);
        return $this->parsingXml($xmlResponse);
    }

    /**
     * Старт бота
     *
     * @param int $type - типа парсинга
     */
    public function start() {
        $this->_configure();
        parent::start();

        if (isset($this->_params['type']) && self::CONF_WITH_DOT == $this->_params['type']) {
            $this->_parseDot();
        } else {
            $this->_parseSimple();
        }
    }

    /**
     * Стандартный парсинг
     * @param array $keywords
     */
    private function _parseSimple() {
        try {
            DB::begin();
            // получаем сет
            $setId = (int) DB::execute(se_StmtDaemon::prepare(se_StmtDaemon::GET_SET_FREE))->fetchColumn(0);
            if (!$setId) {
                die('Empty SetId');
            }        
            
            DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::SET_USED_SET),
                    array(':set_id' => $setId, ':search_type' => 'YaXml', ':status' => 'Ok')
                    );
            

            // получаем ключевики сета
            $aKeywords = DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::GET_KEYWORDS_BY_SET),
                    array(':set_id' => $setId)
                    )->fetchALL(PDO::FETCH_ASSOC);
            
            // блочим ключевики в newindex
            
            
            
        } catch (Exception $e) {
            DB::rollback();
             Log::warning("Rollback\n");
        }

        var_dump($aKeywords);die;
        foreach ($keywords as $kwId => $kwData) {
            
            // получаем регион
            //формируем массив для сета
            //парсим
            // если не получилось делаем пазу на 500мс и поновой если опять не удача
            // добавляем слово в список не пропарсенных, ставим отметку что дублируем запрос
            //идем дальше
        }
        


        if (count($keywords) > 0) {
            return;
            Log::warning('Не найдены ключевики у сета');
        }
        
        foreach ($keywords as $kw) {
            $this->curl->setGet(array('lr' => $kw['region_id']));
            $parseKw = $this->parsing($kw);
            $p = new sep_Positions();
            foreach($parseKw as $pos => $info) {
                $sql = Stmt::prepare2(se_StmtDaemon::GET_SITE, array('name' => $info['site']));
                $aSite = DBExt::getOneRowSql($sql);
                if (!$aSite) {
                    $site = new sep_Sites();
                    $site->name = $info['site'];
                    $site->save();
                    $siteId = $site->id;
                } else {
                    $siteId = $aSite['id'];
                }


                $sql = Stmt::prepare2(se_StmtDaemon::GET_URL, array('url' => $info['url']));
                $aUrl = DBExt::getOneRowSql($sql);
                if (!$aUrl) {
                    $url = new sep_Urls();
                    $url->url = $info['url'];
                    $url->site_id = $siteId;
                    $url->save();
                    $urlId = $url->id;
                } else {
                    $urlId = $aUrl['id'];
                }
                $p->keyword_id = $kw['id'];
                $p->site_id = $siteId;
                $p->url_id = $urlId;
                $p->pos = $pos;
                $p->links_search = $info['links_search'];
                $p->saveInBuffer()->reset();
            }
            $p->executeBuffer();
        }
        
    }

    /**
     * Парсинг с "точкой"
     * @param array $keywords
     */
    private function _parseDot($keywords) {
        foreach ($keywords as $kw){
           $this->curl->setGet(array('lr' => $kw->region_id));
           $psDot = $this->parsing($kw, self::CONF_WITH_DOT);
           if ($psDot) {
               $ps = $this->parsing($kw);
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
         //  Log::warning('');
        }
    }
}