<?php
/**
 * Класс Curl - ООП обвертка стандартной curl-lib
 *
 * @author Alexander Suslov a.s.suslov@gmail.com
 */
require_once CORE_PATH . 'Lib/ConstCurl.php';
require_once CORE_PATH . 'Lib/Mimetypes.php';

class Curl {

    public
        $_referer = 'http://www.google.com',
        $_cookie;
    
    protected
        // ссылка на текущий курл
        $_ch,
        //массив опций для курла
        $_opt = array(),
        //массив информации о выполненном запросе
        $_info = array(),
        //хранит результат запроса, пока его не распарсят
        $_responseRaw,
        // "Тело" ответа, без заголовков
        $_responseBody,
        // массив заголовоков ответа
        $_aResponseHeaders,
        // кодировка ответа, если не указана - будет браться из заголовков ответа
        $_responseCharset = 'cp1251', // can use UNKNOW, if unkonow encoding in response
        $_responseMimeType = 'text/html',
        // Массив get-параметров для запроса
        $_aGet = array(),
        // Массив post-параметров для запроса
        $_aPost = array(),
        // Массив заголовков для запроса
        $_aRequestHeaders = array(),
        // Массив файлов для запроса
        $_aRequestFiles,
        //ошибки
        $_errors = array(),
        $_aResponseCookies = array(),
        $_responseLocation;

    public function __construct() {
        $session = RegistrySession::instance();
        if ($session->is('curl_referer')) {
            $this->_referer = $session->get('curl_referer');
        }
        if ($session->is('curl_cookie')) {
            $this->_cookie = $session->get('curl_cookie');
        }
        $this->_ch = curl_init();
    }

    public function __destruct() {
        $session = RegistrySession::instance();
        $session->set('curl_cookie', $this->_cookie); 
        $session->set('curl_referer', $this->_referer);
    }

    /**
     * Скачать файл с сервера
     * NO STABLE
     * @param string $url - адрес картинки
     * @param string $path - куда сохранять
     * @return bool
     */
    public function downloadFile($url, $path) {
        $this->setOpt(CURLOPT_URL, $url);
        if ($this->_exec()) {
            $this->_parseResponse(false);
            $file = $this->_responseBody;
        } else {
            return false;
        }
        @unlink($path);
        $fd = fopen($path, "a");
        fwrite($fd, $file);
        fclose($fd);
        return true;
    }
    
    /**
     * Выполняет запрос и все необходимые действия
     * @return Curl
     */
    public function request($url) {
        $this->_clean();
        $aGet = $this->_preparedGet();

        if ($aGet) {
            $url = $url . '?' . $aGet;
        }
        if ($this->isOpt(CURLOPT_POST)) {
            $this->_preparePost();
        }
        $this->setOpt(CURLOPT_URL, $url);

        if ($this->_exec()) {
            $this->_referer = $url;
            $this->_parseResponse();
        }
        return $this;
    }

    /**
     * Выполнить Get запрос
     * @param string $url - url, без get-параметров
     */
    public function requestGet($url) {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
        $this->setOpt(CURLOPT_POST, false);
        return $this->request($url);
    }

    /**
     * Выполнить Post запрос
     * @param string $url - url, без get-параметров
     */
    public function requestPost($url) {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setOpt(CURLOPT_POST, true);
        return $this->request($url);
    }

    /**
     * Добавить файл к запросу
     * @param string $key
     * @param string $pathToFile
     */
    function setFile($key, $pathToFile) {

    }

    /**
     * Получить файл
     * @param string $key
     * @return string path to file
     */
    function getFile($key) {
        return path;
    }

    /**
     * Задать массив Post
     * @param array $array
     */
    function setPost (array $array) {
        $this->_aPost = $this->_aPost + $array;
    }

    /**
     * Получить массив Post
     * @return array
     */
    function getPost () {
        return $this->_aPost;
    }

    /**
     * Задать массив Get
     * @param array $array
     */
    function setGet(array $array) {
        $this->_aGet = $this->_aGet + $array;
    }

    /**
     * Получить массив Get
     * @return array
     */
    function getGet() {
        return $this->_aGet;
    }

    /**
     * Задать заголовки запроса
     */
    function setHeaders(array $aHeaders) {
        $this->_aRequestHeaders = array_replace($this->_aRequestHeaders, $aHeaders);
    }

    /**
     * Получить заголовки запроса
     */
    function getHeaders() {
        return $this->_aRequestHeaders;
    }

    function reset() {
        $this->resetCookies();
        $this->resetReferer();
    }

    function resetReferer() {
        $this->setReferer();
    }

    /**
     * Задать рефер
     */
    function setReferer($referer = null) {
        $session = RegistrySession::instance();
        if ($referer) {
            $session->del('curl_referer');
        } else {
            $session->set('curl_referer', $referer);
        }
        $this->_referer = $referer;
    }
    /**
     * Сбросить куки и другую информацию которую хранит курл для себя между вызовами.
     */
    public function resetCookies() {
        $session = RegistrySession::instance();
        $session->del('curl_cookie');
        $this->_cookie = '';
    }
    /**
     * Формирует строку из get-параметров
     * Применяет к параметрам urldecode
     * например key1=123&key2=a%20b&key2
     * @return string
     */
    protected function _preparedGet() {
        return TFormat::prepareQueryString($this->_aGet);
    }

    /**
     * Формирует тело Post запроса
     */
    protected function _preparePost() {
        $post = array();
        foreach ($this->_aPost as $key => $value) {
            $post[] = urlencode($key) . (($value === '') ? '=' : '=' . urlencode($value));
        }
        $this->setOpt(CURLOPT_POSTFIELDS, implode('&' , $post));
    }

    /**
     * Формирует строку заголовков
     */
    protected function _preparedHeaders() {
        $this->setHeaders(array(
            'Accept'	 => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'User-Agent' => 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.10 (maverick) Firefox/3.6.12',
            'Accept-Language' => 'ru,en-us;q=0.7,en;q=0.3',
            'Accept-Charset' =>	'windows-1251,utf-8;q=0.7,*;q=0.7',
            'Connection' => 'keep-alive',
            'Keep-Alive' => '115',
            ));

         if ($this->_referer) {
             $this->setHeaders(array('Referer' => $this->_referer));
         }

         if ($this->_cookie) {
             $this->setOpt(CURLOPT_COOKIE, $this->_cookie);
         //    $this->setHeaders(array('Cookie' => $this->_cookie));
         }

                
        if ($this->_aRequestHeaders) {
            $this->setOpt(CURLOPT_HTTPHEADER, $this->_aRequestHeaders);
        }
    }

    /**
     * Выполнить как ajax-запрос
     */
    function asAjax() {
        $this->setHeaders(array('X-Requested-With' => 'XMLHttpRequest',
                                'Content-Type'     => 'application/x-www-form-urlencoded; charset=UTF-8;'));
    }

    /**
     * Возвращает тело ответа
     * @return string Utf-8
     */
    public function getResponseBody() {
        return $this->_responseBody;
    }

    /**
     * Возвращает массив заголовков
     * @return array
     */
    public function getResponseHeaders() {
        return $this->_aResponseHeaders;
    }

    /**
     * Задать настройки курла из массива
     * @param array $opt массив настроек для curl
     */
    public function setOptArray(array $opt = array()) {
         $this->_opt = $this->_opt + $opt;
    }

    /**
     * Задать настройку для курла
     * @param int $key
     * @param mixed $value
     */
    public function setOpt($key, $value) {
         $this->_opt[$key] = $value;
    }

    /**
     * Получить информацию о последнем действии
     * @param string $key
     * @return mixed
     */
    public function getinfo($key = null) {
        $this->_info = curl_getinfo($this->_ch);
        if($key) {
            return $this->_info[$key];
        } else {
            return $this->_info;
        }
    }

    /**
     * получить ошибки
     * @return array
     */
    public function getErrors() {
        return $this->_errors;
    }

    /**
     * Узнать если такое с-во в опциях курла
     * @param string $name название опции
     * @return mixed
     */
    public function isOpt($name) {
        return isset($this->_opt[$name]) ? true : false;
    }

    public function getOpt($name) {
        return $this->isOpt($name) ? $this->_opt[$name] : null;
    }

    /**
     * Возвращает массив всех настроек курла
     * 
     * @param bool $analogView - вывести в человекопонятном виде
     * @return array
     */
    public function getOpts($analogView = false) {
        if ($analogView) {
            $analogOpt = array();
            foreach($this->_opt as $key => $val) {
                if (isset(ConstCurl::$opts[$key])) {
                    $analogOpt[ConstCurl::$opts[$key]] = $val;
                } else {
                    $analogOpt[$key] = $val;
                }
            }
            return $analogOpt;
        } else {
            return $this->_opt;
        }
    }
    
    /**
     * Выполнить курл с текущими настройками
     * @return bool удачно или нет
     */
    protected function _exec() {
        $this->_preparedHeaders();
 //       RunTimer::addPoint('Curl');


        curl_setopt_array($this->_ch, $this->_opt);
        Log::dump($this->getOpts(true));
        $this->_responseRaw = curl_exec($this->_ch);

        //FIXME: сделать нормальное логирование ошибок. общее и для текущией итерации
        $this->_errors = array();
        $this->getinfo();

        //сохраняем рефер
        $this->_referer = $this->getinfo("url");
        if (!$this->getinfo('http_code')) {
            $this->_errors[] = 'Не удалось выполнить операцию';
        }

     //   RunTimer::endPoint('Curl');
        return $this->_responseRaw ? true : false;
    }

    /**
     * Парсит строку хидеров.
     * Также пытается получить кодировку, mime-type, cookies и location
     * @param string $response
     * @return array хидеры в виде массива
     * @todo отрефакторить цикл
     */
    protected function _parseHeaders() {
        $aHeaders = array();
        $sHeaders = substr($this->_responseRaw, 0, $this->_info['header_size']);
        $sHeaders = TFormat::winTextToLinux($sHeaders);
        $sHeaders = preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $sHeaders);
        $rows = explode("\n", $sHeaders);
        foreach ($rows as $row) {
            if(preg_match('/([a-zA-Z-]+): (.+)/', $row, $matches)){
                $headerName = strtolower($matches[1]);
                $headerValues = preg_split("/;\s*/", $matches[2]);
                foreach($headerValues as $subValue) {
                    switch ($headerName) {
                        case ConstCurl::HEADER_CONTENT_TYPE:
                            if (preg_match('/charset=(.+)/i', $subValue, $subMatch)) {
                                $this->_responseCharset = $subMatch[1];
                            } else if (Mimetypes::is($subValue)){
                                $this->_responseMimeType = $subValue;
                            }
                            break;
                        case ConstCurl::HEADER_SET_COOKIE:
                            $this->_aResponseCookies[] = $subValue;
                            
                            break;
                        case ConstCurl::HEADER_LOCATION:
                            $this->_responseLocation = $subValue;
                    }
                 }
                 $aHeaders[$matches[1]] = $matches[2];
             }
         }
        if (isset($this->_aResponseCookies[0])) {
            $this->_cookie = $this->_aResponseCookies[0];
        }

        return $this->_aResponseHeaders = $aHeaders;
    }

    /**
     * Парсит ответ
     * Получает массив заголовков и строку тела в UTF-8 кодировке
     */
    protected function _parseResponse($convert = true) {
        // если в конфигурации стоит получение заголовков
        if ($this->isOpt(CURLOPT_HEADER)) {
            $this->_aResponseHeaders = $this->_parseHeaders();
        }

        if (!$this->isOpt(CURLOPT_NOBODY)) {
            $this->_responseBody = substr($this->_responseRaw, $this->_info['header_size']);
            if ($convert && $this->_responseCharset && ENCODING_CODE) {
                $this->_responseBody = @iconv($this->_responseCharset, ENCODING_CODE, $this->_responseBody);
            }
        }
        $this->_responseRaw = null;
    }

    /**
     * Очищает данные, полученные из прошлого запроса
     */
    protected function _clean() {
        $this->_responseBody = null;
        $this->_aResponseHeaders = null;
        $this->_info = null;
    }

    public function close() {
        if ($this->_ch) {
            curl_close($this->_ch);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Задать кодировку сайта
     */
    public function setResponseCharset($charset) {
        $this->_responseCharset = $charset;
    }


    /**
     * Задать кодировку сайта
     */
    public function getResponseMimeType($charset) {
        $this->_responseMimeType;
    }
}