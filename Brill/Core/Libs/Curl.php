<?php
/**
 * Класс Curl - ООП обвертка стандартной curl-lib
 *
 * @author Alexander Suslov a.s.suslov@gmail.com
 */

class Curl {
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
        $_charsetResponse = 'windows-1251', // can use UNKNOW, if unkonow encoding in response
        // Массив get-параметров для запроса
        $_aGet = array(),
        // Массив post-параметров для запроса
        $_aPost,
        // Массив заголовков для запроса
        $_aRequestHeaders,
        // Массив файлов для запроса
        $_aRequestFiles;

    public function __construct() {

    }

    /**
     * Выполняет запрос и все необходимые действия
     * @return Curl
     */
    public function request($url) {
        $this->_clean();
        $sGet = $this->_preparedGet();
        $headers = $this->_preparedHeaders();
        if ($aGet) {
            $url = $url . '?' . $sGet;
        }
        if ($this->exec($url)) {
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
        $this->_aPost = array_merge($this->_aPost, $array);
    }

    /**
     * Задать массив Get
     * @param array $array
     */
    function setGet(array $array) {
        $this->_aGet = array_merge($this->_aGet, $array);
    }

    /**
     * Получить массив Post
     * @return array
     */
    function getPost () {
        return $this->_aPost;
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
    function setHeaders() {

    }

    /**
     * Получить заголовки запроса
     */
    function getHeaders() {

    }
    /**
     * Формирует строку из get-параметров
     * Применяет к параметрам urldecode
     * например key1=123&key2=a%20b&key2
     * @return string
     */
    protected function _preparedGet() {
        $get = array();
        foreach ($this->_aGet as $key => $value) {
            $get[] = $key . ($value === '') ? '' : '=' . urldecode($value);
        }
        return implode('&' . $get);
    }

    /**
     * Формирует строку заголовков
     */
    protected function _preparedHeaders() {

    }

    /**
     * Выполнить как ajax-запрос
     */
    function asAjax() {
        $this->setHeaders();
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
         $this->_opt = array_merge($this->_opt, $opt);
    }

    public function setOpt($key, $value) {
         $this->_opt[$key] = $value;
    }
    public function getinfo() {
        return $this->_info = curl_getinfo($this->_ch);
    }

    /**
     * Узнать если такое с-во в опциях курла
     * @param string $name название опции
     * @return mixed
     */
    public function isOpt($name) {
        return isset($this->_opt[$name]) ? (bool)$this->_opt[$name] : null;
    }

    /**
     * Выполнить курл с текущими настройками
     * @return bool удачно или нет
     */
    protected function _exec($get = '', $post = null) {
        RunTimer::addPoint('Curl');
        //задаем урл
        curl_setopt_array($_ch, $this->_opt);
        $this->_responseRaw = curl_exec($_ch);
        RunTimer::endPoint('Curl');
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
        $sHeaders = strtolower(StringUtf8::substr($this->_responseRaw, 0,
                $this->_info['header_size']));
        $sHeaders = TFormat::winTextToLinux($sHeaders);
        $rows = explode("\n", $sHeaders);
        foreach ($rows as $row) {
            $rr = explode(': ', $row, 2);
            $keyHeader = $rr[0];
            $valueHeader = $rr[1];
            $aHeaders[$keyHeader] = $valueHeader;

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
        return $this->_aResponseHeaders = $aHeaders;
    }

    /**
     * Парсит ответ
     * Получает массив заголовков и строку тела в UTF-8 кодировке
     */
    protected function _parseResponse() {
        // если кодировка задана заранее
        if ($this->_charsetResponse && ENCODING_CODE != $this->_responseRaw ) {
            $this->_responseRaw = @iconv($this->_charsetResponse, ENCODING_CODE, $this->_responseRaw);
        }

        // если в конфигурации стоит получение заголовков
        if ($this->isOpt(CURLOPT_HEADER)) {
            $this->_aResponseHeaders = $this->_headersToArray();
        }

        if (!$this->isOpt(CURLOPT_NOBODY)) {
            // если не известна кодировка - получаем ее из заголовоков
//            if (isset($this->_responseHeaders['Content-Type']['charset']) && !$this->_charsetResponse) {
//                $this->_responseRaw = @iconv($this->_responseHeaders['Content-Type']['charset'],
//                                             ENCODING_CODE,
//                                             $this->_responseRaw);
//            }
            $this->_responseBody = StringUtf8::substr($response, -$info['download_content_length']);
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

}