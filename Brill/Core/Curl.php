<?php
/*
 * class-wrapper Curl's
 *
 */

class Curl {
    protected
        // ссылка на курл
        $_ch,
        //массив опций для курла
        $_opt = array(),
        //массив информации о выполненном запросе
        $_info = array(),
        //хранит результат запроса, пока его не распарсят
        $_responseRaw,
        $_responseBody,
        $_aResponseHeaders,
        $_charsetResponse,
        $_charsetRequest = 'windows-1251',
        $_aGet = array(),
        $_aPost,
        $_aRequestHeaders,
        $_aFiles;

    public function __construct() {
        // TODO Убрать заглушку
        $this->_charsetResponse  = 'windows-1251';
    }

    /**
     * Выполняет запрос и все необходимые действия
     * @return bool удачно или нет
     */
    public function request($url) {
        $this->_clean();
        $sGet = $this->_preparedGetArray();
        if ($aGet) {
            $url = $url . '?' . $sGet;
        }
        if ($this->exec($url)) {
            $this->_info = curl_getinfo($this->_ch);
        } else {
            $this->_info = null;
        }
        $this->_parseResponse();
        return $this;
    }

    public function requestGet($url) {
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
        if (is_array($this->send_param_get)){
            foreach ($this->send_param_get as $key => $value){
               if (!empty($key)) $for_sen_action_url .= $key . '=' . urlencode($value) . '&';
            }
        }
    }

    public function requestGet() {

    }

    function setPostArray (array $array) {
        $this->_aPost = array_merge($this->_aPost, $array);
    }

    function setGetArray() {
        $this->_aPost = array_merge($this->_aPost, $array);
    }

    protected function _preparedGet() {
        $get = '';
        foreach ($this->_aGet as $key => $value) {
            $get .= $key . ($value === '') ? '' : '=' . urldecode($value);
        }
        return $get;
    }
    function setHeadersArray() {

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

    }

}
