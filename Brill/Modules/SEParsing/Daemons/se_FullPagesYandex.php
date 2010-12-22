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
    const VERSION           = '0.2Light';    //Версия сего продукта
    const MAX_LINKS_YA      = 1000;     //Максимальное количество страниц отдаваемое яндексом
    const GROUP_ON_PAGE     = 100;       //Сколько ссылок запрашивать на одной странице,
    const DEBUG_LEVEL       = 55;        //Уровень отладки
    const MAX_COUNT_REQUEST = 700;       //Для отладки. После скольких запросов остановится
	public $is_simple			= true;		//запускать только один симпл
    public $site               = false;       //Название сайта
	public $count_pages		= 10;
    public $site_id            = false;
    public $infoParse          = '';
    public $globalCountYa      = 0;        //Всего найденных Яндексом страниц
    public $countRequest       = 0;        //количество произведенных запросов к Яндексу
    public $countGather        = 0;        //Количество пройденных страниц
    public $countTextGather    = 0;        //Количество собранных HTML-страниц
    public $countSimpleParser  = 0;        //Количество запусков симпла
    public $dateBegin          = 0;        //начало скрипта
    public $dateCurl           = 0;        //Для отладки.время работы на соединение
    public $newlinks			= 0;		//добавлено новых ссылок
    public $ch                 = false;    //Сеанс Curl
    public $db;                            //Класс для работы с базой(базами)
    public $alfabetic          = array(    //Все символы. [дата последнего изменения списка: 12.02.2010]
                                    'a'=>1,'b'=>1,'c'=>1,'d'=>1,'e'=>1,'f'=>1,'g'=>1,'h'=>1,
                                    'i'=>1,'j'=>1,'k'=>1,'l'=>1,'m'=>1,'n'=>1,'o'=>1,'p'=>1,
                                    'q'=>1,'r'=>1,'s'=>1,'t'=>1,'u'=>1,'v'=>1,'w'=>1,'x'=>1,'y'=>1,'z'=>1,
                                    '/'=>1,'$'=>1,'&'=>1,'='=>1,'-'=>1,'_'=>1,'.'=>1,'?'=>1,'%'=>1,','=>1,'!'=>1,
                                    '0'=>1,'1'=>1,'2'=>1,'3'=>1,'4'=>1,'5'=>1,'6'=>1,'7'=>1,'8'=>1,'9'=>1
                                );



    protected function _configure() {
        require_once $this->_module->pathModels . 'sep_YaPagesSites.php';
        require_once $this->_module->pathModels . 'sep_Projects.php';
    }


     /*
      * получает свободный IP
      */
    function getIp(){
        return $this->getInterface();
    }

    //получает все url'ы
    function getUrls($xml_response){
        $pattern = '/<url>http:\/\/'.$this->site.'\/(.*?(?=<\/url>))<\/url>.*?(<mime-type>(.*?(?=<\/mime-type>))<\/mime-type>)/';
        preg_match_all ($pattern, $xml_response, $match);

        return $match[1];//если сортировать - происходит глюк с обработкой алфавита
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

     /* Input:
      * $xml_response - XML ответ Яндекса
      * Output:
      * $countYa - количество ссылок найденных яндексом
      */
    function getCountYa($xml_response){
        $pattern = '/<found priority=\"strict\">(.*?(?=<\/found>))<\/found>/';
        preg_match($pattern, $xml_response, $mathces);
        (isset($mathces[1])) ? $countYa = intval($mathces[1]) : $countYa = 0;
        return $countYa;
    }


     /* Input:
      * $xml_response - XML ответ Яндекса
      * Output:
      * $xml_response - очищенный XML
      */
    function clearYaXml($xml_response){
        $xml_response = substr ($xml_response, strpos ($xml_response, '<?xml'));
        return $xml_response;
    }

//    //Отправляет запрос и получает XML ответ у Яндекса
//    function requestYandex($dop, $page = 0){
//       if(self::DEBUG_LEVEL>=5)  echo 'Запрос к Яндексу: '.$this->site.'/'.$dop;
//        if($this->countRequest==self::MAX_COUNT_REQUEST && self::MAX_COUNT_REQUEST!=0) {
//
//          //  $this->__destruct(); //сработало ограничение на количество запросов
//            exit();
//        }
//        if(self::DEBUG_LEVEL>=3) echo ' [<b>'.$dop.'</b>, <i>'. $page.'</i>]->';
//        $ip=$this->getip();
//        if(self::DEBUG_LEVEL>=2) echo ' IP:'.$ip;
//        $query = 'url:'.$this->site.'/'.$dop;
//        $request=$this->getXMLRequest($query, $page);
//        $beginCurl=microtime(1);
//        if(!$this->ch){
//            $this->ch = curl_init ();
//            curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt ($this->ch, CURLOPT_TIMEOUT, 6000);
//            curl_setopt ($this->ch, CURLOPT_URL, 'http://xmlsearch.yandex.ru/xmlsearch?rd=0');
//            curl_setopt ($this->ch, CURLOPT_POST, 1);
//        }
//
//     //   curl_setopt ($this->ch, CURLOPT_INTERFACE, $ip);
//        curl_setopt ($this->ch, CURLOPT_POSTFIELDS, 'text='.$request);
//        $xml_response = curl_exec ($this->ch);
//
//        $this->countRequest++;
//
//        $this->dateCurl += microtime(1) - $beginCurl;
//
//        if(self::DEBUG_LEVEL>=6)  	echo '<pre>'. htmlspecialchars($xml_response).'</pre>';
//        return $xml_response;
//    }

    /*
     * Простой парсер, собирает у Яндекса все страницы.
     * Максимум 20 страниц, с 50 ссылками на каждой
     */
    function simpleParser($dop = 0, $depth = 1, $countYa = 0, $xml_response = false){
        $gatherSimple=0;        //отладочная, количество пройденных страниц симплом
        $gatherTextSimple=0;    //отладочная, html страниц найденных симплом
        $this->countSimpleParser++;


        // проходим все страницы. у яндекса при 50 линках на странице - максимум 20 страниц
        for ($page = 0; $page < $depth; $page++)
        {
          if($page == 0 && $xml_response){

		  }else{
            $query = 'url:'.$this->site.'/'.$dop;
            $request=$this->getXMLRequest($query, $page);
            $xml_response=$this->requestYandex($request);//отправляем запрос яндексу и получаем XML
		  }
           // if(!$xml_response)  break;

            $this->clearYaXml($xml_response);
            preg_match_all ('/<url>(.*?(?=<\/url>))<\/url>/', $xml_response , $match);
            preg_match_all ('#<mime-type>(.*?(?=<\/mime-type>))<\/mime-type>#', $xml_response , $match2);
            $finded = false;
            if(self::DEBUG_LEVEL>=3)  echo 'go! <div style="font-family:arial;font-size:8pt;padding:4px;">';

            foreach ($match[1] as $key=>$urly)
            {
                if(isset($match2[1][$key]))
                {
                    $mime=$match2[1][$key];
                    $gatherSimple++;
                    $this->countGather++;
                    if(self::DEBUG_LEVEL>=4)    echo '<i>'.$urly.'</i> ';
                    if($mime=='text/html')// берем только html-ссылки
                    {
                        $sHost = html_entity_decode(preg_replace('~http://~', '',$urly));
                        $path=preg_replace('~'.$this->site.'~', '',$sHost);
                        $this->newlinks+=$this->db->setBlock($path);  // сверка с базой

                        $this->countTextGather++;
                        $gatherTextSimple++;
                        if(self::DEBUG_LEVEL>=4) echo '<b>(текстовая)</b><br>';
                   }

                }
            }
           if(self::DEBUG_LEVEL>=3)  echo '</div>';

        }
        if(self::DEBUG_LEVEL>=2) echo $this->site.'/<b>'.$dop.'</b> PAGE: '.$page.', Добавлено ссылок:'.$gatherTextSimple.', всего пройдено '.$gatherSimple.'<br>';

        return $gatherSimple;
    }



    // рекурсивный парсер
    function parse($dop='', $pcount = -1){
        $query = 'url:'.$this->site.'/'.$dop . '*';
        $request=$this->getXMLRequest($query);

        $xml_response=$this->requestYandex($request);


        $groups    = $xml_response->results->grouping->group;
        $countYa = (int)$groups->doccount;
        $i         = 0;
        $ps = array();

        Log::dump($xml_response->results->grouping);
        foreach ($groups as $value) {
//            $pos++;
           $url = (string) $value->doc->url;
           Log::dump($url);
//            $parsedUrl = @parse_url($url);
//            $ps[$pos]['site'] = $parsedUrl['host'];//
//            $ps[$pos]['url'] = $url;
//            $ps[$pos]['links_search'] = (string) $value->doc->properties->_PassagesType;//найдено по ссылке
//            $positions[md5($url)] = $ps;
        }

        Log::dump($xml_response);
        die('=-=--=');


        if(!$xml_response) return 0;
        $this->clearYaXml($xml_response);
        $countYa=$this->getCountYa($xml_response);//получаем количество страниц от яндекса !!! оптимизировать, сделать нормальный парсер ЯНдекса
        $depthLink=strlen($dop);// глубина урла

        if($this->first){   //первый запрос
             $this->first=false;
             $this->globalCountYa=$countYa;
        }
        if(self::DEBUG_LEVEL>=5) echo ' страниц: '.$countYa.'<br>';
        if($countYa==0) return 0;// нет страниц

        if($countYa<=self::MAX_LINKS_YA || $this->is_simple){
            // если количество страниц меньше 1000 - запускаем простой обработчик ссылок
                $depth=ceil($countYa/self::GROUP_ON_PAGE-0.01); //  получаем количество страниц
				if($depth>$this->count_pages) $depth=$this->count_pages;
                if(self::DEBUG_LEVEL>=2) echo '<br> <b>New Simple Parser</b>. links: '.$countYa.' pages:'.$depth.'<br>';
                return $this->simpleParser($dop, $depth, $countYa, $xml_response); // нашли узел с количеством ссылок <=1000, запускаем симпл
        }

        $urls=array(); // список урлов для утяжеления

        $urls=$this->getUrls($xml_response);
        if(self::DEBUG_LEVEL>=6)   var_dump($urls);
        foreach($urls as $key => $value){
            $urls[$key]=strtolower($value);
        }
        $alfa=$this->getWeightedArray($urls,$depthLink);// "утежеляет" массив $alfa, на основе полученных данных
        arsort($alfa);
        if(self::DEBUG_LEVEL>=6)   var_dump($alfa);




        if($this->globalCountYa > $this->countGather)
        {
            reset($alfa);
            $gatherCurWord=0;//сколько уже собрано по этому слову
           // if($pcount==$countYa)
            foreach ($alfa as $key => $value) {
                 //   if($countGather>=$globalCountYa) break;//выходим, если все собрали

                    $gatherCurWord+=$this->parse($dop.$key,$countYa);
			 echo "<h3>Cобрали по ***}".$dop."{*** : ".$gatherCurWord." страниц из ".$countYa."</h3>";
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
//        $kpd = (($this->countGather/($this->countRequest))/self::GROUP_ON_PAGE)*100;
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
        $sql = Stmt::prepare(se_StmtDaemon::GET_PROJECT_SITES, array('limit' => 1));
        $site = Model::getObjectsFromSql('sep_Projects', $sql);
        
        $this->site = $site[0]->site;
        Log::dump($site[0]->toArray());
        if (!$site) {
            die ('--');//'Закончились ключевики');
        }
	//	$this->unsetBlocksYndex();

		$this->count_pages = ceil(self::MAX_LINKS_YA/self::GROUP_ON_PAGE-0.01);
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

