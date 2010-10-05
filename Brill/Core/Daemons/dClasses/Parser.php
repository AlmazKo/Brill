<?php
/**
 * Description of Parser
 *
 * @author almaz
 */

require_once 'Keywords.php';

abstract class Parser
{
    protected $curl;
    protected $curl_opt = array();
    protected $count_request;

    public function  __construct($settings) {
        RunTimer::addTimer('Curl');
    }
    /**
     *
     * �������� ������� � �� ����� �������� ��������
     * @param Keyword $k  �������� �� z_keywords
     *
     */
    abstract protected function parsing(Keywords $k);

    /**
     *
     * �������� ip ��� �������
     * @return string IP �����
     *
     */
    abstract protected function getIp();


    /**
     * �������� ������ Get-�������
     * @return string GET-������
     *
     */
    protected function getGET(){}

    /**
     * �������� ������ POST-�������
     * @return string POST-������
     *
     */
    protected function getPOST(){
        return null;
    }

    final protected function request(){
        RunTimer::addPoint('Curl');
        curl_setopt_array($this->curl, $this->curl_opt);
        $response = curl_exec ($this->curl);
        RunTimer::endPoint('Curl');
        return $response;
    }

    final protected function initCurl(){
        $this->curl = curl_init ();
        $this->curl_opt[CURLOPT_HEADER] = false;
        $this->curl_opt[CURLOPT_RETURNTRANSFER] = true;
        $this->curl_opt[CURLOPT_FOLLOWLOCATION] = false;
        $this->curl_opt[CURLOPT_TIMEOUT] = 30;
    }


    final function  __destruct() {
            if($this->curl) curl_close ($this->curl);


    }

    abstract public function run(RegistryParser $config);

}

