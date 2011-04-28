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
        $_linksInPages = 100;

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

        require_once $this->_module->pathDaemons . 'Exceptions/YandexXmlException.php';
        require_once $this->_module->pathDaemons . 'Exceptions/LimitInterfacesException.php';
        
        
        require_once $this->_module->pathDaemons . 'Mocks/getMockResultParsingYaXml.php';
        
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
        }
        return $ps;
    }

    /**
     *
     * @param array $keyword
     * @param int $dot тип парсинга
     * @return array
     */
    protected function parsing($keyword, $conf = 0) {
        $query = $keyword['kw_keyword'] . ((self::CONF_WITH_DOT == $conf) ? '.' : '');
        
        $this->curl->setGet(array('lr' => $keyword['rg_region']));
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
                throw new Exception('Empty SetId');
            }   

            echo "\nПолучили сет: id=" . $setId;
            if (!DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::SET_USED_SET),
                    array(':set_id' => $setId, ':search_type' => 'YaXml', ':status' => 'Busy')
                    )->rowCount()) {
                throw new Exception('Error blocking set');
            }


            // получаем ключевики сета
            $aKeywords = DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::GET_KEYWORDS_BY_SET),
                    array(':set_id' => $setId)
                    )->fetchALL(PDO::FETCH_ASSOC);
            
            // блочим ключевики в newindex

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            // чтото не получилось, отдаем назад все
            throw new Warning($e->getMessage());
        }
        
        echo "\nВзято ключевиков для обработки: " . count($aKeywords);
        $today8 = mktime (8, 0, 0, date ('m'), date ('d'), date ('Y'));
        
        foreach ($aKeywords as &$kw) {
            $kw['pos'] = 0;
            $kw['error'] = false;
            try {
                if (!$kw['rg_region']) {
                    $kw['rg_region'] = self::DEFAULT_REGION_ID;
                }
                $result = $this->parsing($kw);
                //$result = getMockResultParsingYaXml();
                /*
                 * Mock 
                 * $result = getMockResultParsingYaXml();
                 */
               
                foreach ($result as $key => $sePos) {
                    if (strtolower($this->getHost($sePos['url'])) === strtolower($this->getHost($kw['kw_url']))) {
                        $kw['pos'] = $key;
                        $kw['url_search'] = $sePos['url'];
                        $kw['links_search'] = $sePos['links_search'];
                        break;
                    }
                }
                
                
                // сохранение этой фигни
              //  throw new LimitInterfacesException('');
//                if (!rand(0,5)) {
//                    throw new YandexXmlException('Ошибка в XML-запросе — проверьте валидность отправляемого XML и корректность параметров');
//                }
//                if (!rand(0,8)) {
//                    throw new LimitInterfacesException('');
//                }
                
              // формируем полученные данные для SeoComp
             // сохраняем эти данные, ставим отметку ключевику об успешном парсинге
             //сохраняем в массив сета ключевиков, нашли ли наш
            } catch (YandexXmlException $e) {
                /* 
                 * яндекс нас послал подальше
                 * ставим ключевик ошибку
                 */
                $kw['error'] = true;
                echo "\nYandex error for keyword[" .$kw['kw_id'] . "]: " . $e->getMessage() . '';
                continue;

            } catch (LimitInterfacesException $e) {
                //закончились айпи
                unset($kw['pos']);
                echo "\nЗаконочились ip на keyword[" .$kw['kw_id'] . "]: " . $e->getMessage() . ''; 
                break;
            } catch (Exception $e) {
                unset($kw['pos']);
                echo "\nЧертовщина с keyword[" .$kw['kw_id'] . "]: " . $e->getMessage() . ''; 
                break;
            }
             echo "\n Yandex OK keyword[" .$kw['kw_id'] . "]!";
             
            // должно упроститься до DBExt::getColumn(se_StmtDaemon::GET_SEOCOMP_YA)
//
//            $seoComp = DB::execute(se_StmtDaemon::prepare(se_StmtDaemon::GET_SEOCOMP_YA),
//                                      array(':parent' => $setId, ':date' => $today8, ':keyword' => $kw['kw_keyword'])
//                                  )->fetchColumn(0);
//            if (!$seoComp) {
                $strSeoComp = $this->createStringForSeoComp($result);
                echo "\nВставляем в seocomp информацию по КЧ `" .$kw['kw_keyword'] . "`"; 
                DB::execute(se_StmtDaemon::prepare(se_StmtDaemon::SET_SEOCOMP_YA),
                            array(  ':parent'   => $setId, 
                                    ':date'     => $today8, 
                                    ':seoh'     => $strSeoComp,
                                    ':keyword'  => $kw['kw_keyword'],
                                    ':range'    => $kw['kw_range'],
                                    ':premiya'  => $kw['kw_premiya'])
                            );
    //    }
        
             
        } 
        
        echo "\n";
        $successKeywords = self::getListSuccessfullyKeywords($aKeywords);
        $failKeywords = self::getListFailKeywords($aKeywords);
        echo "\nПропарсенных ключевиков: " . count($successKeywords) ; 
        echo "\nОшибки при парсинге : " . count($failKeywords) . ' ключевиков'; 
        
        

        if ($successKeywords) {
            DB::exec('UPDATE webexpert_acc.z_keywords SET kw_parsed = "1" WHERE kw_id in ('.implode(',',$successKeywords).')');
        }
        if ($failKeywords) {
            DB::exec('UPDATE webexpert_acc.z_keywords SET kw_parsed = "3" WHERE kw_id in ('.implode(',',$failKeywords).')');
        }
        
        
        

        $strSeo = $this->createStringForSeo($aKeywords);
        
        $result = DB::execute("SELECT se_id FROM webexpert_acc.z_seo WHERE se_parent = ".$setId." AND se_date = ".$today8.' limit 0,1')->fetchColumn(0);
        if ($result){
            // обновляет катенацией, а надо сделать обновление умной вставкой
            // пока сделано тупой вставкой 
            DB::exec('UPDATE webexpert_acc.z_seo SET se_poss = "'.$strSeo.'" WHERE se_id = '.(int)$result);
        } else {
            DB::exec ('INSERT INTO webexpert_acc.z_seo SET se_parent = '.$setId.', se_date = '.$today8.', se_poss = "'.$strSeo.'"');
        }        

        if (!DB::execute(se_StmtDaemon::prepare(
                se_StmtDaemon::SET_USED_SET),
                array(':set_id' => $setId, ':search_type' => 'YaXml', ':status' => 'Ok')
                )->rowCount()) {
            throw new Exception('Error blocking set');
        }
 /*       
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
        */
     //   var_dump(LogMysql::getLog());
        echo "\n...Ok...\n ";
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
    
    function createStringForSeo (array $keywords) {
        $tmpArr = array();
        foreach ($keywords as  $kw) {
            $str = $kw['kw_keyword'];
            if (empty($kw['pos'])) {
                $str .= '|-|-|-|' . $kw['kw_url'];
            } else {
                $str .= '|'.$kw['pos'].'|'.$kw['links_search'].'|'.$kw['url_search'].'|' . $kw['kw_url'];
            }
            $tmpArr[] = $str;
        }
        //TODO проверить сепаратор!
        return "\n" . implode("\n", $tmpArr);
    }
    
    /**
     * Формирует строку для вставки в таблицу SeoComp
     * @param array $result
     * @return string
     */
    function createStringForSeoComp(array $result) {
        $tmpArr = array();
        foreach ($result as $key => $value) {
            $tmpArr[] = $key . '|' . $value['url'];
        }
        //TODO проверить сепаратор!
        return implode("\n", $tmpArr);
    }
    
    
    public static function getListFailKeywords (array &$aKeywords) {
        $list = array();
        foreach ($aKeywords as $keyword) {
            if (isset($keyword['pos']) && !empty($keyword['error'])) {
                $list[] = $keyword['kw_id'];
            }
        }
        return $list;
    }
    
    public static function getListSuccessfullyKeywords (array &$aKeywords) {
        $list = array();
        foreach ($aKeywords as $keyword) {
            if (isset($keyword['pos']) && empty($keyword['error'])) {
                $list[] = $keyword['kw_id'];
            }
        }
        return $list;
    }
}