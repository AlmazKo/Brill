<?php
/**
 * Класс Curl - ООП обвертка стандартной curl-lib
 *
 * @author Alexander Suslov a.s.suslov@gmail.com
 */
require_once CORE_PATH . 'Lib/ConstCurl.php';

class Curl {

    public
        $_referer='http://www.press-release.ru/',
        $cookie;
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
        $_charsetResponse = 'cp1251', // can use UNKNOW, if unkonow encoding in response
        // Массив get-параметров для запроса
        $_aGet = array(),
        // Массив post-параметров для запроса
        $_aPost = array(),
        // Массив заголовков для запроса
        $_aRequestHeaders = array(),
        // Массив файлов для запроса
        $_aRequestFiles,
        //ошибки
        $_errors = array();

    public function __construct() {
        $this->_session = RegistrySession::instance();
        if ($this->_session->is('curl_refer')) {
            $this->_referer = $this->_session->get('curl_refer');
        }

        echo 'давай!!куку!!!! ';
        var_dump($this->_session);
        if ($this->_session->is('cookie')) {
            echo 'получаем куку!!!! ';
            $this->cookie = $this->_session->get('cookie');
        }
         
        $this->_ch = curl_init();
        RunTimer::addTimer('Curl');

    }

    public function __destruct() {
        $this->_session = RegistrySession::instance();


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
           # Log::dump($this->_info);
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
        return $this->request($url);
    }

    /**
     * Выполнить Post запрос
     * @param string $url - url, без get-параметров
     */
    public function requestPost($url) {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setOpt(CURLOPT_POST, 1);
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
    
    /**
     * Сбросить куки
     */
    function resetCookies() {
        $this->_session = RegistrySession::instance();
        $this->_session->del('cookie');
        $this->cookie = null;


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
            $post[] = $key . (($value === '') ? '=' : '=' . $value);
        }
        $this->setOpt(CURLOPT_POSTFIELDS, implode('&' , $post));
    }

    /**
     * Формирует строку заголовков
     */
    protected function _preparedHeaders() {
        if ($this->_referer && $this->cookie) {
            $this->setHeaders(array('Referer' => $this->_referer, 
               // 'Cache-Control' => 'max-age=0',
                'User-Agent' => 'Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.2.12) Gecko/20101027 Ubuntu/10.10 (maverick) Firefox/3.6.12',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Keep-Alive' => '115',
                'Connection' => 'keep-alive',
                'Refer' =>$this->_referer
                ));
            $this->setOpt(CURLOPT_COOKIE, $this->cookie);
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
        //задаем урл
        curl_setopt_array($this->_ch, $this->_opt);
        log::dump($this->getOpts(true));
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
     * Разбирает строку хидеров на массив
     * @param string $response
     * @return array
     * ~~~ /[\u21-\u7E]\:\s([^\u10\u13]+)$/
     * /^([a-z_]+)\:\s([^\n]+)$/
     *
     * /(\w+)(\;\s([a-z_-]+)(\=[a-z])?)? /
     */
    protected function _headersToArray() {

        $aHeaders = array();
        $sHeaders = strtolower(substr($this->_responseRaw, 0,
                $this->_info['header_size']));
        $sHeaders = TFormat::winTextToLinux($sHeaders);
        $sHeaders = preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $sHeaders);
        $rows = explode("\n", $sHeaders);
        foreach ($rows as $row) {
            $rr = explode(': ', $row, 2);
            if (count($rr) == 2) {
                $keyHeader = $rr[0];
                $valueHeader = $rr[1];
                if ('set-cookie' == $keyHeader && !$this->cookie) {
                    $ssid = explode('; ', $valueHeader, 2);
                    $this->cookie = $ssid[0];
                    echo 'ПОЛУЧИЛИ КУКУ '. $this->cookie;
                }
//                if (preg_match('/([^=]+)=(.+)/', $subject, $m2)) {
//                    $val[$m2[1]] = $m2[2];
//                } else {
//                    $val[] = $match;
//                }
                $aHeaders[$keyHeader] = $valueHeader;
            }


// надо заменить на регулярку
// http://ru.wikipedia.org/wiki/%D0%97%D0%B0%D0%B3%D0%BE%D0%BB%D0%BE%D0%B2%D0%BA%D0%B8_HTTP#.D0.9E.D0.B1.D1.89.D0.B8.D0.B9_.D1.84.D0.BE.D1.80.D0.BC.D0.B0.D1.82
//            if (strpos($valueHeader, '; ')) {
//                $aValueHeader = explode('; ', $valueHeader);
//                foreach ($aValueHeader as $value) {
//                    if ('content-type' == $keyHeader) {
//                        if (Mimetypes::is($value)) {
//                            $newRow['mime'] = $value;
//                        }
//
//                       if (strpos($value[1], '=')) {
//                           $rrrr = explode('=', $value, 2);
//                           $newRow['charset'] = $rrrr[1];
//                       }
//                    } else {
//                        if (strpos($value[1], '=')) {
//                           $rrrr = explode('=', $value, 2);
//                           $newRow[] = $rrrr[1];
//                       }
//                    }
//                }
//
//            }

        }
        echo 'сохраняем куку!!!!';
        $this->_session->set('cookie', $this->cookie);
        $this->_session->set('curl_refer', $this->_referer);
        return $this->_aResponseHeaders = $aHeaders;
    }

    /**
     * Парсит ответ
     * Получает массив заголовков и строку тела в UTF-8 кодировке
     */
    protected function _parseResponse($convert = true) {

        // если в конфигурации стоит получение заголовков
        if ($this->isOpt(CURLOPT_HEADER)) {
            $this->_aResponseHeaders = $this->_headersToArray();
          //  Log::dump($this->_aResponseHeaders);
        }
log::dump($this->_aResponseHeaders);
        if (!$this->isOpt(CURLOPT_NOBODY)) {
            // если не известна кодировка - получаем ее из заголовоков
//            if (isset($this->_responseHeaders['Content-Type']['charset']) && !$this->_charsetResponse) {
//                $this->_responseRaw = @iconv($this->_responseHeaders['Content-Type']['charset'],
//                                             ENCODING_CODE,
//                                             $this->_responseRaw);
//            }

           $this->_responseBody = substr($this->_responseRaw, $this->_info['header_size']);
           // если кодировка задана заранее
            if ($convert && $this->_charsetResponse && ENCODING_CODE ) {
                $this->_responseBody = @iconv($this->_charsetResponse, ENCODING_CODE, $this->_responseBody);
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
        curl_close($this->_ch);
    }

    /**
     * Задать кодировку сайта
     */
    public function setCharsetResponse ($charset) {
        $this->_charsetResponse = $charset;
    }

}