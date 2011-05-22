<?php
/**
 * Класс Curl - ООП обвертка стандартной curl-lib
 *
 * @author almazko <a.s.suslov@gmail.com>
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
        //массив информации о последнем выполненном запросе
        $_info = array(),
        //хранит сырой результат запроса, пока его не распарсят
        $_responseRaw,
        // "Тело" ответа, без заголовков
        $_responseBody,
        // массив заголовоков ответа
        $_aResponseHeaders,
        // кодировка ответа, если не указана - будет браться из заголовков ответа
        $_responseCharset = 'cp1251', 
        // кодировка тела, после всех манипуляций
        $_responseBodyCharset = ENCODING_CODE,
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
        // массив Cookies отсылаемых сервером
        $_aResponseCookies = array(),
        // location, отсылаемый сервером. Т.е. куда хотят нас послать
        $_responseLocation,
        // способ отправки формы серверу
        $_formEnctype = ConstCurl::FORM_ENCTYPE_APP,
        //URL-encodes
        $_prepare = false,
        $_responseMyCharset,
        $_responseMyCharsetForcibly;

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
     * Задать прокси
     * @param string $address
     * @param int $port
     * @param string $login
     * @param string $password 
     */
    public function setProxy($address, $port, $login, $password) {
        $this->curl->setOpt(CURLOPT_INTERFACE, null);
        $this->curl->setOpt(CURLOPT_PROXY, $address);
        $this->curl->setOpt(CURLOPT_PROXYPORT, $port);
        $this->curl->setOpt(CURLOPT_PROXYUSERPWD, $login . ':' . $password);
    }

    function disableProxy() {
        $this->curl->setOpt(CURLOPT_PROXY, null);
     }
    /**
     * Скачать файл с сервера
     * NO STABLE
     * @param string $url - адрес картинки
     * @param string $path - куда сохранять
     * @return bool
     */
    public function downloadFile($url, $path) {
        $this->_clean();
        $this->_preparePost();
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
         //TODO сделать по нормальному и всунуть в демона    
         echo "\nCurl GET: " . $url;
        if ($this->getOpt(CURLOPT_POST)) {
            $this->_preparePost();
        }
        $this->setOpt(CURLOPT_URL, $url);

        if ($this->_exec()) {
            $this->_referer = $this->getinfo("url");
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
        $this->_aPost = array_replace($this->_aPost, $array);
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
        $this->_aGet = array_replace($this->_aGet, $array);
    }

    /**
     * Получить массив Get
     * @return array
     */
    function getGet() {
        return $this->_aGet;
    }

    /**
     * Получить url, который будет использоваться в запросе.
     * Собирается из get-параметров
     * @param string $url url, к которому присоденится  результат
     * @return string
     */
    function getUrl($url = '') {
        $strGet = $this->_preparedGet();

        if ($strGet) {
            $url = (string) $url . '?' . $strGet;
        } else {
            $url = (string) $url;
        }
        return $url;
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
     * Указать способ отправки данных формой
     * @param string $encType
     */
    public function setFormEnctype($encType = ConstCurl::FORM_ENCTYPE_APP) {
        if (ConstCurl::FORM_ENCTYPE_MULTIPART != $encType) {
            $encType = ConstCurl::FORM_ENCTYPE_APP;
        }
        $this->_formEnctype = $encType;
    }
    
    /**
     * Производить конвертирование запроса в urlencode()
     * @param string $encType
     */
    public function setPrepared($value = true) {
        $this->_prepare = (bool) $value;
    }
    /**
     * Формирует строку из get-параметров
     * Применяет к параметрам urldecode
     * например key1=123&key2=a%20b&key2
     * @return string
     */
    protected function _preparedGet($prepare = true) {
        $get = array();
        foreach ($this->_aGet as $key => $value) {
            if ($this->_prepare) {
                $get[] = urlencode($key) . (($value === '') ? '=' : '=' . urlencode($value));
            } else {
                $get[] = $key . (($value === '') ? '=' : '=' . $value);
            }
        }
        return implode('&' , $get);
    }

    /**
     * Формирует тело Post запроса
     */
    protected function _preparePost($prepare = true) {
        if ($this->getOpt(CURLOPT_POST)) {
            $post = array();
            if (ConstCurl::FORM_ENCTYPE_APP == $this->_formEnctype) {
                foreach ($this->_aPost as $key => $value) {
                    if ($this->_prepare) {
                        $post[] = urlencode($key) . (($value === '') ? '=' : '=' . urlencode($value));
                    } else {
                        $post[] = $key . (($value === '') ? '=' : '=' . $value);
                    }
                }
                $this->setOpt(CURLOPT_POSTFIELDS, implode('&' , $post));
            } else {
                foreach ($this->_aPost as $key => $value) {
                    $post[$key] = @iconv(ENCODING_CODE, $this->_responseCharset, $value);
                }
                $this->setOpt(CURLOPT_POSTFIELDS, $post);
            }
        } else {
            $this->delOpt(CURLOPT_POSTFIELDS);
        }
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

    public function delOpt($key) {
        if ($this->isOpt($key)) {
            unset($this->_opt[$key]);
        }
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

    public function getCharsetBody() {
        return $this->_responseBodyCharset;
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
        echo "\nCOOKIE: " . $this->_cookie . "\n\n";
        $this->_preparedHeaders();
        curl_setopt_array($this->_ch, $this->_opt);
       // Log::dump($this->getOpts(true));

        $this->_responseRaw = curl_exec($this->_ch);
        $this->_responseLocation = null;
        //FIXME: сделать нормальное логирование ошибок. общее и для текущией итерации
        $this->_errors = array();
        $this->getinfo();
//Log::dump($this->getinfo());
//Log::dump($this->_responseRaw);

        //сохраняем рефер
        
        if (!$this->getinfo('http_code')) {
            $this->_errors[] = 'Не удалось выполнить операцию';
            return;
        }
        
        if (preg_match('/[45][0-9][0-9]/', $this->getinfo('http_code'))) {
            $this->_errors[] = 'Сервер ответил с ошибкой '. $this->getinfo('http_code');
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
        $this->_aResponseCookies = array();
        $aHeaders = array();
        $sHeaders = substr($this->_responseRaw, 0, $this->_info['header_size']);
        $sHeaders = TFormat::winTextToLinux($sHeaders);
        $sHeaders = preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $sHeaders);
        $rows = explode("\n", $sHeaders);
        foreach ($rows as $row) {
            if (preg_match('/([a-zA-Z-]+): (.+)/', $row, $matches)) {
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
            $this->_cookie = implode('; ', $this->_aResponseCookies);
        }
//Log::dump($aHeaders);
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
        if ($this->_responseLocation || preg_match('/30[0-9]/', $this->getinfo('http_code'))) {

            if (@parse_url($this->_responseLocation, PHP_URL_HOST)) {
                $urlRedirect = $this->_responseLocation;
            } else {
                $url = $this->getinfo('url');
                $parseUrl = @parse_url($url);
                $urlRedirect = $parseUrl['scheme'] . '://' . $parseUrl['host'] . $this->_responseLocation;
            }

            /*
             * http://ru.wikipedia.org/wiki/Список_кодов_состояния_HTTP
             */
            $this->_clean();
            if ('302' == $this->getinfo('http_code') || '303' == $this->getinfo('http_code')) {
                $this->_aPost = array();
                $this->_aGet = array();
                $this->delOpt(CURLOPT_POSTFIELDS);
                $this->requestGet($urlRedirect);
            } else {
                $this->request($url);
            }
            return;
        }
        if (!$this->isOpt(CURLOPT_NOBODY)) {
            $this->_responseBody = substr($this->_responseRaw, $this->_info['header_size']);
            if ($this->_responseMyCharsetForcibly) {
                $charset = $this->_responseMyCharset;
            } else if (!$this->_responseCharset && $this->_responseMyCharset) {
                $charset = $this->_responseMyCharset;
            } else {
                $charset = $this->_responseCharset;
            }
            $this->_responseBodyCharset = $charset;
            if ($convert && $charset && ENCODING_CODE != $charset) {
                $this->_responseBody = @iconv($charset, ENCODING_CODE, $this->_responseBody);
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

    /**
     * Закрывает соединение
     * @return bool 
     */
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
     * @param string $charset
     * @param bool $forcibly - применять ее принудительно или только если не смогли получить ее от сайта
     */

    public function setResponseCharset($charset, $forcibly = false) {
        $this->_responseMyCharset = $charset;
        $this->_responseMyCharsetForcibly = $forcibly;
    }


    /**
     * Задать кодировку сайта
     */
    public function getResponseMimeType($charset) {
        $this->_responseMimeType;
    }

    public function ping($host) {
        
    }
}