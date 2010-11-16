<?php
/* 
 * class-wrapper Curl's
 * 
 */

class Curl {
    protected 
        $_ch,
        $_opt = array(),
        $_info = array(),
        $_responseRaw,
        $_responseBody,
        $_aResponseHeaders,
        $_charsetResponse,
        $_charsetRequest = 'windows-1251';


    public function __construct($opt = array()) {
        $this->_charsetResponse  = 'windows-1251';
        if ($opt) {
            
        }
    }
    public function exec() {
        RunTimer::addPoint('Curl');
        curl_setopt_array($_ch, $this->_opt);
        $this->_responseRaw = curl_exec($_ch);
        RunTimer::endPoint('Curl');
        return $this->_responseRaw ? true : false;
    }

    public function request() {
        $this->clean();
        $this->exec();
        $this->_info = curl_getinfo($this->_ch);
        $this->_parseResponse();

    }

    public function setOptArray(array $opt = array()) {
         array_merge($this->_opt, $opt);
    }
    
    public function getinfo() {
        return $this->_info = curl_getinfo($this->_ch);
    }

    function isOpt($name) {
        return isset($this->_opt[$name]);
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
        $sHeaders = strtolower(StringUtf8::substr($this->_responseRaw, 0, $this->_info['header_size']));
        $sHeaders = TFormat::winTextToLinux($sHeaders);
        $rows = explode("\n", $sHeaders);
        foreach ($rows as $row) {

            $rr = explode(': ', $row, 2);
            $keyHeader = $rr[0];
            $valueHeader = $rr[1];
            if (strpos($valueHeader, '; ')) {
                $aValueHeader = explode('; ', $valueHeader);
                foreach ($aValueHeader as $value) {
                    if ('content-type' == $keyHeader) {
                        if (Mimetypes::is($value)) {
                            $newRow['mime'] = $value;
                        }

                       if (strpos($value[1], '=')) {
                           $rrrr = explode('=', $value, 2);
                           $newRow['charset'] = $rrrr[1];
                       }
                    } else {
                        if (strpos($value[1], '=')) {
                           $rrrr = explode('=', $value, 2);
                           $newRow[] = $rrrr[1];
                       }
                    }
                }

            }
           
        }
        return $aHeaders;
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
            if (isset($this->_responseHeaders['Content-Type']['charset']) && !$this->_charsetResponse) {
                $this->_responseRaw = @iconv($this->_responseHeaders['Content-Type']['charset'],
                                             ENCODING_CODE,
                                             $this->_responseRaw);
            }
            $this->_responseBody = StringUtf8::substr($response, -$info['download_content_length']);
        }
        $this->_responseRaw = null;
    }
    
    /**
     * Возвращает тело ответа
     * @return string Utf-8
     */
    protected function getResponseBody() {
        return $this->_responseBody;
    }

    /**
     * Возвращает массив заголовков
     * @return array
     */
    protected function getResponseHeaders() {
        return $this->_aResponseHeaders;
    }
}

