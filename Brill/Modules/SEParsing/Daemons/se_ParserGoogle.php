<?php

/**
 *  ParserYandexXml
 *
 * Парсит Yandex.Xml
 * @author almaz
 */
class se_ParserGoogle extends se_Parser {
    const URL_SEARCH = 'http://www.google.ru/search';
    protected
        $_lnk2;

    protected
        $_depth = 1,
        //сколько позиций брать за раз
        $_linksInPages = 60;

    public function  __construct() {
        parent::__construct();
        unset(self::$_cliParams[Daemon::KEY_NAME_DAEMON]);
        self::$_cliParams[Cli::ARG_INFO] = 'Демон проверки наличия страниц в выдаче Google';
                $opt = array (CURLOPT_HEADER => true,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_FOLLOWLOCATION => false,
                      CURLOPT_TIMEOUT => 20,
                      CURLOPT_CONNECTTIMEOUT => 7,
                      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3"
                     );
        $this->curl->setOptArray($opt);
        //protected static $_cliParams = array('n' => 'Daemon\'s name.', 'h' => 'View help');
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
     *
     * @param type $keyword
     * @param type $depth 
     */
    protected function parsing($keyword, $depth = 3) {
        $urls = array();
        for ($page = 0; $page < $depth; $page++) {
            $this->curl->setGet(array(
                 'hl'       => 'ru', 
                 'q'        => rawurlencode($keyword['kw_keyword']),
                 'start'    => $page * 10
            ));

            $response = $this->curl->requestGet(self::URL_SEARCH)->getResponseBody();
           // $response = file_get_contents($this->_module->pathDaemons . 'Mocks/googleAnswer.html');
            preg_match_all ('~<h3 class="r"><a href="(.*?)" (.*?)</h3>~', $response, $match);
            if (empty($match[1])) {
                 throw new GoogleException($sxe->error);
            }
           $urls = array_merge($urls, $match[1]);
        }
        var_dump($urls);
        die;
        return $urls;
    }

    /**
     * Старт бота
     *
     * @param int $type - типа парсинга
     */
    public function start() {
        $this->_configure();
        parent::start();
        $this->_parse();
    }

    /**
     * Стандартный парсинг
     * @param array $keywords
     */
    private function _parse() {
        try {
            DB::begin();
            // получаем сет
            
            $setId = (int) DB::execute(se_StmtDaemon::prepare(se_StmtDaemon::GET_SET_FREE_GOOGLE))->fetchColumn(0);
            if (!$setId) {
                throw new Exception('Empty SetId');
            }   

            echo "\nПолучили сет: id=" . $setId;
            if (!DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::SET_USED_SET),
                    array(':set_id' => $setId, ':search_type' => 'Google', ':status' => 'Busy')
                    )->rowCount()) {
                throw new Exception('Error blocking set');
            }


            // получаем ключевики сета
            $aKeywords = DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::GET_KEYWORDS_BY_SET_GOOGLE),
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
 
        echo "\n...Ok...\n ";
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