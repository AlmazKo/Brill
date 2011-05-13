<?php

/**
 *  ParserYandexXml
 *
 * Парсит Yandex.Xml
 * @author almaz
 */
class se_ParserGoogle extends se_Parser {
    protected
        $_lnk2;

    protected
        $_depth = 1,
        //сколько позиций брать за раз
        $_linksInPages = 60;

    public function  __construct() {
        parent::__construct();
        unset(self::$_cliParams[Daemon::KEY_NAME_DAEMON]);
        self::$_cliParams += array('s' => 'Название стратегии парсинга');
        self::$_cliParams += array('set' => 'Id сета. Пересобрать все ключевики этого сета');
        self::$_cliParams += array('keyword' => 'Id ключевика, который надо пересобрать');
        self::$_cliParams += array('view' => 'Вывод информации');
        self::$_cliParams += array('region' => 'Парсить с учетом id этого региона');
        self::$_cliParams += array('no-save' => "Не сохранять результат парсинга. " .
                                                "Фиксация сета происходит все равно на время парсинга");
        self::$_cliParams[Cli::ARG_INFO] = 'Демон проверки наличия страниц в выдаче Google';
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
     *
     * @param type $setId
     * @return array Keyword 
     */
    protected function _getKeywordsByRandomSet() {
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
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            // чтото не получилось, отдаем назад все
            throw new Warning($e->getMessage());
        }
        $collectionKeywords = array();
        foreach ($aKeywords as $kw) {
            $collectionKeywords[] = new Keyword($kw);
        }
        return $collectionKeywords;
    }
    
    /**
     * Получить список ключевиков сета
     * 
     * @param int $setId
     * @return Keyword 
     */
    function _getKeywordsBySetId($setId) {
        echo "\nПересборка сета id=" . $setId;
        try {
            DB::begin();
            // получаем сет
            $result = DB::execute(se_StmtDaemon::prepare(se_StmtDaemon::GET_SET_GOOGLE_BY_ID),
                                       array(':set_id' => $setId))->fetch(PDO::FETCH_ASSOC);
            if (is_null($result)) {
                throw new Exception('Not found SetId=' . $setId);
            }
            
            if ('Busy' === $result['status']) {
                throw new Exception('Set id=' . $setId . ' is busy');
            }
            
            if (!$result['active']) {
                throw new Exception('Set id=' . $setId . ' isn\'t  active');
            }
            
            if (!DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::SET_USED_SET),
                    array(':set_id' => $setId, ':search_type' => 'Google', ':status' => 'Busy')
                    )->rowCount()) {
                throw new Exception('Error: blocking set');
            }
 // сбрасываем данные по ключевикам сета
            $this->_setStatusKeywordsBySet($setId);
            // получаем ключевики сета
            $aKeywords = DB::execute(se_StmtDaemon::prepare(
                    se_StmtDaemon::GET_KEYWORDS_BY_SET_GOOGLE),
                    array(':set_id' => $setId)
                    )->fetchALL(PDO::FETCH_ASSOC);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            // чтото не получилось, отдаем назад все
            throw new Warning($e->getMessage());
        }
        $collectionKeywords = array();
        foreach ($aKeywords as $kw) {
            $collectionKeywords[] = new Keyword($kw);
        }
        return $collectionKeywords;
    }
    
    function _getKeywords(array  $keywordIds = array()) {
        echo "\nПересборка ключевиков id=" . $setId;

        $collectionKeywords = array();
        return $collectionKeywords;
    }
    
    /**
     * Стандартный парсинг
     * @param array $keywords
     */
    private function _parse() {
                

        switch (true) {
            case isset($this->options['set']):
                $listKeywords = $this->_getKeywordsBySetId((int)611);
                break;
            
            case isset($this->options['keyword']):
                $listKeywords = $this->_getKeywords((array)$this->options['keyword']);
                break;
            
            default:
                $listKeywords = $this->_getKeywordsByRandomSet();
        }

        echo "\nВзято ключевиков для обработки: " . count($listKeywords);
        $today8 = mktime (8, 0, 0, date ('m'), date ('d'), date ('Y'));
              
    #    var_dump($listKeywords); die;
 
        foreach ($listKeywords as $keyword) {
            try {
                $positions = $this->_strategy->parse($keyword, 60);
//                if (!rand(0,5)) {
//                    throw new YandexXmlException('Ошибка в XML-запросе — проверьте валидность отправляемого XML и корректность параметров');
//                }
//                if (!rand(0,8)) {
//                    throw new LimitInterfacesException('');
//                }
            } catch (GoogleException $e) {
                /* 
                 * яндекс нас послал подальше
                 * ставим ключевик ошибку
                 */
                $keyword->error = true;
                echo "\nYandex error for keyword[" .$keyword['kw_id'] . "]: " . $e->getMessage() . '';
                continue;
            } catch (LimitInterfacesException $e) {
                //закончились айпи
                $keyword->status = false;
                echo "\nЗаконочились ip на keyword[" .$keyword['kw_id'] . "]: " . $e->getMessage() . ''; 
                break;
            } catch (Exception $e) {
                $keyword->status = false;
                echo "\nЧертовщина с keyword[" .$keyword['kw_id'] . "]: " . $e->getMessage() . ''; 
                break;
            }
            
            $keyword->status = true;    
            foreach ($positions as $position => $item) {
                if (strtolower($this->getHost($item['url'])) === strtolower($this->getHost($keyword['kw_url']))) {
                    $keyword->position = $item['pos'];
                    $keyword->detectedUrl = $item['url'];
                    $keyword->foundByLink = $item['links_search'];
                    break;
                }
            }

               // формируем полученные данные для SeoComp
              // сохраняем эти данные, ставим отметку ключевику об успешном парсинге
             //сохраняем в массив сета ключевиков, нашли ли наш

             echo "\n Google OK keyword[" .$keyword->id . "]!";
             $this->_saveInSeoComp($setId, $keyword, $positions, $today8);
        } 
        
        echo "\n";
        $this->_updateStatusKeywords($listKeywords);
        $this->_saveInSeo($setId, $listKeywords, $today8);
        echo "\n...Ok...\n ";
    }

 
    protected function _createStringForSeo (array $listKeywords) {
        $tmpArr = array();
        foreach ($listKeywords as  $keyword) {
            $str = $keyword;
            if (empty($keyword->pos)) {
                $str .= '|-|-|-|' . $keyword->url;
            } else {
                $str .= '|' . $keyword->position . '|' . $keyword->foundByLink . '|'.$keyword->detectedUrl . '|' . $keyword->kw_url;
            }
            $tmpArr[] = $str;
        }
        return "\n" . implode("\n", $tmpArr);
    }
    
    /**
     * Формирует строку для вставки в таблицу SeoComp
     * @param array $positions
     * @return string
     */
    protected function _createStringForSeoComp(array $positions) {
        $tmpArr = array();
        foreach ($positions as $key => $value) {
            $tmpArr[] = $key . '|' . $value['url'];
        }
        return implode("\n", $tmpArr);
    }
    
    /**
     * Сохраняет данные по резльтам поиска по одному ключевику
     * @param int $setId
     * @param Keyword $keyword
     * @param array $positions
     * @param int $today8 
     */
    protected function _saveInSeoComp($setId, Keyword $keyword, array $positions, $today8) {
        $strSeoComp = $this->_createStringForSeoComp($positions);
        echo "\nВставляем в seocomp информацию по Ключевику `" . $keyword . "`"; 
        DB::execute(se_StmtDaemon::prepare(se_StmtDaemon::SET_SEOCOMP_YA),
                    array(  ':parent'   => $setId, 
                            ':date'     => $today8, 
                            ':seoh'     => $strSeoComp,
                            ':keyword'  => $keyword,
                            ':range'    => $keyword->range,
                            ':premiya'  => $keyword->premiya)
                    );
    }
    
    /**
     * Сохраняет данные по всем ключевикам в таблицу z_seo
     * @param int $setId
     * @param array $listKeywords
     * @param int $today8 
     */
    protected function _saveInSeo ($setId, array $listKeywords, $today8) {
        $strSeo = $this->_createStringForSeo($listKeywords);

        $seId = DB::execute("SELECT se_id FROM webexpert_acc.z_seo WHERE se_parent = " . $setId . " AND se_date = ".$today8.' limit 0,1')->fetchColumn(0);
        if ($seId){
            // обновляет катенацией, а надо сделать обновление умной вставкой
            // пока сделано тупой вставкой 
            DB::exec('UPDATE webexpert_acc.z_seo SET se_poss = "'.$strSeo.'" WHERE se_id = '.(int)$seId);
        } else {
            DB::exec ('INSERT INTO webexpert_acc.z_seo SET se_parent = '.$setId.', se_date = '.$today8.', se_poss = "'.$strSeo.'"');
        }        

        if (!DB::execute(se_StmtDaemon::prepare(
                se_StmtDaemon::SET_USED_SET),
                array(':set_id' => $setId, ':search_type' => 'YaXml', ':status' => 'Ok')
                )->rowCount()) {
            throw new Exception('Error blocking set');
        }  
    }
    
    /**
     *
     * @param type $listKeywords 
     */
    protected function _updateStatusKeywords($listKeywords) {
        $successKeywords = self::_getListSuccessfullyKeywords($listKeywords);
        $failKeywords = self::_getListFailKeywords($listKeywords);
        echo "\nПропарсенных ключевиков: " . count($successKeywords) ; 
        echo "\nОшибки при парсинге : " . count($failKeywords) . ' ключевиков'; 

        if ($successKeywords) {
            $this->_resetStatusKeywords($successKeywords, 1);
           # DB::exec('UPDATE webexpert_acc.z_keywords SET kw_parsed = "1" WHERE kw_id in (' . implode(',', $successKeywords) . ')');
        }
        if ($failKeywords) {
            $this->_resetStatusKeywords($successKeywords, 3);
           # DB::exec('UPDATE webexpert_acc.z_keywords SET kw_parsed = "3" WHERE kw_id in (' . implode(',', $failKeywords) . ')');
        }
    }
    
    /**
     *
     * @param int $setId
     * @param int $value 
     */
    protected function _setStatusKeywordsBySet($setId, $value = 0) {
        DB::exec('UPDATE webexpert_acc.z_keywords SET kw_parsed = ' . $value .' WHERE kw_parent= ' .$setId);
    }
    
    protected function _resetStatusKeywords(array $keywordIds = array(), $value = 0) {
        DB::exec('UPDATE webexpert_acc.z_keywords SET kw_parsed = '.$value.' WHERE kw_id in (' . implode(',', $keywordIds) . ')');
    }
    
    protected static function _getListSuccessfullyKeywords (array &$aKeywords) {
        $list = array();
        foreach ($aKeywords as $keyword) {
            if (isset($keyword['pos']) && empty($keyword['error'])) {
                $list[] = $keyword['kw_id'];
            }
        }
        return $list;
    }
    
    protected static function _getListFailKeywords (array &$aKeywords) {
        $list = array();
        foreach ($aKeywords as $keyword) {
            if (isset($keyword['pos']) && !empty($keyword['error'])) {
                $list[] = $keyword['kw_id'];
            }
        }
        return $list;
    }
}