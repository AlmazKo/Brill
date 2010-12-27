<?php
/* DEBUG_LEVEL
 * 0 - чистый
 * 1 - выводит общую статистику
 * 2 - выводит, запрашиваемые IP, сколько собрал каждый Simple Parser
 * 5 - выводит все запросы
 */
/*
interface IDB_Rotator{
    function getIP(); //получает IP, и делает сразу у него декремент
    function setBlock($url); // Добавление/обновление блока
    function updateSite($id); //обновляет информацию о сайте, заносит статистику и дату обновления
}
*/

Class se_FullPagesYandex extends se_YandexXml
{
    const 
        VERSION           = '0.3',    //Версия сего продукта
        DEBUG_LEVEL       = 0,        //Уровень отладки
        MAX_COUNT_REQUEST = 700;       //Для отладки. После скольких запросов остановится
    

    protected
        $_linksInPages = 100;
    
	public $is_simple			= true;		//запускать только один симпл
    public $site               = false;       //Название сайта
    public $siteId            = false;
    public $infoParse          = '';
    public $globalCountYa      = 0;        //Всего найденных Яндексом страниц
    public $countRequest       = 0;        //количество произведенных запросов к Яндексу
    public $countGather        = 0;        //Количество пройденных страниц
    public $countTextGather    = 0;        //Количество собранных HTML-страниц
    public $countSimpleParser  = 0;        //Количество запусков симпла
    public $dateBegin          = 0;        //начало скрипта
    public $dateCurl           = 0;        //Для отладки.время работы на соединение
    public $newlinks            = 0;		//добавлено новых ссылок
    public $ch                 = false;    //Сеанс Curl
    public $db;                            //Класс для работы с базой(базами)
    public $alfabetic          = array(    //Все символы. [дата последнего изменения списка: 12.02.2010]
                                    'a'=>1,'b'=>1,'c'=>1,'d'=>1,'e'=>1,'f'=>1,'g'=>1,'h'=>1,
                                    'i'=>1,'j'=>1,'k'=>1,'l'=>1,'m'=>1,'n'=>1,'o'=>1,'p'=>1,
                                    'q'=>1,'r'=>1,'s'=>1,'t'=>1,'u'=>1,'v'=>1,'w'=>1,'x'=>1,'y'=>1,'z'=>1,
                                    '/'=>1,'$'=>1,'&'=>1,'='=>1,'-'=>1,'_'=>1,'.'=>1,'?'=>1,'%'=>1,','=>1,'!'=>1,
                                    '0'=>1,'1'=>1,'2'=>1,'3'=>1,'4'=>1,'5'=>1,'6'=>1,'7'=>1,'8'=>1,'9'=>1
                                );

    public function  __destruct() {
        parent::__destruct();
     //   DB::query(Stmt::prepare2(se_StmtDaemon::SET_PROJECT_SET, array('project_id' => $this->site->id, 'search_type' => 'YaXmlDot', 'status'=>'ok')));
    }

    protected function _configure() {
        require_once $this->_module->pathModels . 'sep_YaPagesSites.php';
        require_once $this->_module->pathModels . 'sep_Projects.php';
    }

    /* Input:
     * $urls - список всех ссылок
     * $depth - порядковый номер символа в ссылке
     * Output:
     * $alfa - утежеленный ALFABETIC, для упрощения работы // + Есть ли смысл?
     */
   function getWeightedArray($urls, $depth){
        $alfa = $this->alfabetic;

        foreach($urls as $key => $value) {
            $symb=$value[$depth];
           if(array_key_exists($symb,$alfa))// проверка, вообщето все символы должны быть
                $alfa[$symb]++;
        }

        return $alfa;
    }

    /**
     * Простой парсер, собирает у Яндекса все страницы.
     * @param int $dop
     * @param int $depth
     * @param int $countYa
     * @param array $urls
     * @return int
     */
    function simpleParser($dop = 0, $depth = 1, $countYa = 0, $urls = array()){
        $gathered = 0;        //отладочная, количество пройденных страниц симплом
        $gatherWebPage = 0;    //отладочная, html страниц найденных симплом
        $this->countSimpleParser++;
        $i = 1;

        
        for ($page = 0; $page < $depth; $page++)
        {
          //для первой страницы не делаем, т.к. запрос уже был сделан выше
            if (!$urls || $page != 0) {
                $query = 'url:'.$this->site->name.'/' . '*';
                $request = $this->getXMLRequest($query, $this->_linksInPages, $page);
                $xmlResponse = $this->requestYandex($request);//отправляем запрос яндексу и получаем XML
                $groups = $xmlResponse->results->grouping->group;
                $urls = $this->_getUrls($groups);
                $countYa = (int)$xmlResponse->results->grouping->found[0];
            }
           
            foreach($urls as $url => $info) {
                $oUrl = new sep_Urls();
                 $this->countGather++;
                 $gathered++;
               
                     $oUrl->getObjectField('url',$url);
                     if ($oUrl->isNull()) {
                         $oUrl->url = $url;
                         $oUrl->mime_type = $info['mime-type'];
                         $oUrl->status = 'is_yandex';
                         $oUrl->site_id = $this->site->id;
                     }
                     $oUrl->save();
                 if (Mimetypes::isWebPage($info['mime-type'])) {
                     $this->countWebPageGather++;
                     $gatherWebPage++;

                 } else {
                    // echo '<br>-'.$i++."\t" .$url;
                 }
            }

        }
        if(self::DEBUG_LEVEL>=2) echo $this->site.'/<b>'.$dop.'</b> PAGE: '.$page.', Добавлено ссылок:'.$gatherWebPage.', всего пройдено '.$gathered.'<br>';
        return $gathered;
    }

    /**
     * Получить массив урлов
     * @param SimpleXMLElement $group
     */
    protected function _getUrls(SimpleXMLElement $groups) {
        $urls = array();
        foreach ($groups as $value) {
           $page = (array)$value->doc;
           $urls[$page['url']] = array(
               'domain' => $page['domain'],
               'mime-type' => $page['mime-type']);
        }
        return $urls;
    }

    /**
     * Рекурсивный парсер
     * @param string $dop
     * @param int $pcount
     * @return int
     */
    function parse($dop = '', $pcount = -1){
        if ($this->first) {
            $query = 'url:'.$this->site->name . '*';
        } else {
            $query = 'url:'.$this->site->name.'/'.$dop . '*';
        }
        
        $request = $this->getXMLRequest($query);
echo ' страниц: '.$request.'<br>';
        $xmlResponse = $this->requestYandex($request, $this->_linksInPages);
log::dump($xmlResponse);
        $groups = $xmlResponse->results->grouping->group;
        $countYa = (int)$xmlResponse->results->grouping->found[0];


        if (!$countYa) {
            return 0;// нет страниц
        }
        if ($this->first) {   //сохраняем количество страниц на все сайте
            $this->first = false;
            $this->globalCountYa = $countYa;
        }
        $urls = $this->_getUrls($groups);
        Log::dump($urls);




        $depthLink = strlen($dop);// глубина урла



       

        if ($countYa <= self::MAX_LINKS_YA || $this->is_simple) {
            // если количество страниц меньше 1000 - запускаем простой обработчик ссылок
            $depth = ceil($countYa/$this->_linksInPages-0.01); //  получаем количество страниц
            

      //  echo '<br> <b>New Simple Parser</b>. links: '.$countYa.' pages:'.$depth.'<br>';
        if ($depth>10) $depth=10;
            return $this->simpleParser($dop, $depth, $countYa, $urls); // нашли узел с количеством ссылок <=1000, запускаем симпл
        }

        foreach($urls as $key => $value){
            $urls[$key]=strtolower($value);
        }
        $alfa=$this->getWeightedArray($urls,$depthLink);// "утежеляет" массив $alfa, на основе полученных данных
        arsort($alfa);
       // if(self::DEBUG_LEVEL>=6)   var_dump($alfa);




        if($this->globalCountYa > $this->countGather)
        {
            reset($alfa);
            $gatherCurWord=0;//сколько уже собрано по этому слову
           // if($pcount==$countYa)

            foreach ($alfa as $key => $value) {
                 //   if($countGather>=$globalCountYa) break;//выходим, если все собрали

                    $gatherCurWord+=$this->parse($dop.$key,$countYa);
		//	 echo "<h3>Cобрали по ***}".$dop."{*** : ".$gatherCurWord." страниц из ".$countYa."</h3>";
                    if($gatherCurWord >= $countYa) break; //останавливаем рекурсию, когда собрали все по конкретному узлу
            }
        }
        return 0;
    }

    function statistic(){
//
//        $stat = 'Сайт: '.$this->site.chr(13).chr(10);
//        $stat .= 'Пройдено ссылок: '.$this->countGather.chr(13).chr(10);
//        $stat .= 'Из них собрано html-ссылок: '.$this->countTextGather.chr(13).chr(10);
//        $stat .= 'Количество запросов: '.$this->countRequest.chr(13).chr(10);
//        $stat .= 'Запусков SimpleParser: '.$this->countSimpleParser.chr(13).chr(10);
//
//        $kpd = (($this->countGather/($this->countRequest))/$this->_linksInPages)*100;
//        $kpd = number_format($kpd, 2, '.', '');
//
//        $timew=(microtime (1) - $this->datebegin);
//        $kpdtime=((($timew - $this->dateCurl)/$timew))*100;
//        $timework = number_format( $timew, 3, '.', '');
//        $kpdtime = number_format( $kpdtime, 1, '.', '');
//        $stat .= 'КПД программы для этого сайта: '.$kpd.'%'.chr(13).chr(10);
//        $stat .= 'Время выполнения скрипта: '. $timework.' сек'.chr(13).chr(10);
//        $stat .= 'Соединение с яндексом и получение данных: '. number_format( $this->dateCurl, 3, '.', '').' сек'.chr(13).chr(10);
//        $stat .= 'КПД времени: '.$kpdtime.'%'.chr(13).chr(10);
//        $this->infoParse=$stat;
//		$this->db->setinfo($this->globalCountYa, $this->countTextGather, $kpd, $timew, $this->newlinks);
//        if(self::DEBUG_LEVEL>=1)  echo '<div style="color:#008800;font-family:Arial;font-size:15px;"><pre>'.$this->infoParse.'</pre></div>';
    }
    function start (){
        $parser->is_simple=true;
        $this->_configure();
        parent::start();

        do {
            $sql = Stmt::prepare(se_StmtDaemon::GET_PROJECT_FREE, array('limit' => 1, 'search_type' => 'YaXmlDot'));
            $project = DBExt::getOneRowSql($sql);
            if (!$project) {
                Log::warning('Закончились проекты');
            }
            $sql = Stmt::prepare2(se_StmtDaemon::SET_PROJECT_SET, array('project_id' => $project['id'], 'search_type' => 'YaXmlDot', 'status' => 'Busy'));

        } while(!DBExt::tryInsert($sql));

        $oSite = new sep_Sites();
        $oSite->getObject($project['site_id']);
        $this->site = $oSite;




	//	$this->unsetBlocksYndex();

	//	$this->_countPages = ceil(self::MAX_LINKS_YA/$this->_linksInPages-0.01);
        $this->first = true;
        
      //  Log::dump($this);
       
        $this->parse();
    }

}

//$parser=new Parsya();
//$parser->is_simple=true;
////$parser->site=false ; адрес сайта, если не задан то ищет последний не обновлявшийся//'ag-club.ru';
//$parser->start();

?>
