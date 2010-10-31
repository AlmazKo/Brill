<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Curl{
    public $_STATUS = null;

    protected $curl = null;
    protected $getInfo = null;
    protected $response = null;

    protected function CurlExt(array $curl_setopt = array()){
print_r($curl_setopt);
//die();
        $curl_setopt[CURLOPT_RETURNTRANSFER] = 1;
        $curl_setopt[CURLOPT_BINARYTRANSFER] = 1;
        $curl_setopt[CURLOPT_HEADER] =  1;
        $curl_setopt[CURLOPT_FOLLOWLOCATION] = 1;
        $curl_setopt[CURLOPT_MAXREDIRS] = 10;

        $curl_setopt[CURLOPT_COOKIEJAR] = 'COOKIES_FILE/jar_test.txt';
        $curl_setopt[CURLOPT_COOKIEFILE] = 'COOKIES_FILE/jar_test.txt';


        if (is_array($curl_setopt)){
            $this->curl = curl_init();
            curl_setopt_array($this->curl, $curl_setopt);
            $this->response = curl_exec($this->curl);
            $this->getInfo = curl_getinfo($this->curl);
            curl_close($this->curl);
        }
        echo $this->response;
        return true;
    }
}

class AS_Bot extends Curl{

    public $action_url = null;
    public $send_param_get = null;
    public $send_param_post = null;
    public $send_headers = null;

    public function RUN($action_url = null, $send_param_get = null, $send_param_post = null, $send_headers = null){
        $this->action_url = $action_url;
        $this->send_param_get = $send_param_get;
        $this->send_param_post = $send_param_post;
        $this->send_headers = $send_headers;
        
        if (empty($action_url)){
            //если урла у правила нет, то все плохо
            $_STATUS = 'function strategy incorrect';
            return false;
        }else{
            if (is_array($this->send_param_post)){
                //если переданы пост параметры, то метод ПОСТ
                $this->Curl_POST_send();
            }else{
                //если не переданы пост параметры, то метод ГЕТ
                $this->Curl_GET_send();
            }
            return true;
        }
    }

    public function Curl_POST_send() {
        $curl_setopt = array();
        $curl_setopt[CURLOPT_URL] = $this->action_url;
        $curl_setopt[CURLOPT_CUSTOMREQUEST] = 'POST';
        $for_sen_action_url = null;
        if (is_array($this->send_param_post)){
            foreach ($this->send_param_post as $key => $value){
               if (!empty($key)) $for_sen_action_url .= $key . '=' . urlencode($value) . '&';
            }
        }
        if (!empty($for_sen_action_url)) $curl_setopt[CURLOPT_POSTFIELDS] = 'username=almaz&password=ALR210411AL&entrance.x=22&entrance.y=15&entrance=%E2%F5%EE%E4';//(string)$for_sen_action_url;
        if ($this->CurlExt($curl_setopt)){
            return true;
        }else{
            return false;
        }
    }

    public function Curl_GET_send() {
        $curl_setopt = array();
        $curl_setopt[CURLOPT_CUSTOMREQUEST] = 'GET';
        $for_sen_action_url = $this->action_url . '?';
        if (is_array($this->send_param_get)){
            foreach ($this->send_param_get as $key => $value){
               if (!empty($key)) $for_sen_action_url .= $key . '=' . urlencode($value) . '&';
            }
        }
        if (!empty($for_sen_action_url)) $curl_setopt[CURLOPT_URL] = $for_sen_action_url;
        if ($this->CurlExt($curl_setopt)){
            return true;
        }else{
            return false;
        }
    }

    public function GetResponseHeaders(){
        $explode_ResponseHTMLsource = explode("\r\n", $this->response);
        foreach ($explode_ResponseHTMLsource as $key => $value){
            if ($value){
                $header_str = explode(":", $value);
                $headers[$header_str[0]] = $header_str[1];
            }else{
                break;
            }
        }
        return $headers;
    }

    public function GetResponseHTMLsource(){
        $explode_ResponseHTMLsource = explode("\r\n", $this->response);
        $body = false;
        $HTMLsource = '';
        foreach ($explode_ResponseHTMLsource as $key => $value){
            if ($value && $body){
                $HTMLsource .= $value . "\r\n";
            };
            if (!$value){
                $body = true;
            }
        }
        return $HTMLsource;
    }


}

