<?php
/**
 * Description of as_Strategy
 *
 * @author almaz
 */
require_once General::$loadedModules['AutoSubmitter']->pathLib . 'as_XmlMapper.php';
require_once General::$loadedModules['AutoSubmitter']->pathLib . 'XmlParser.php';
include_once CORE_PATH. 'Lib/Curl.php';

class as_Strategy {
    protected
        $_userForm,
        $_ruleId = 0,
        $_subscribe,
        $_fieldsSend,
        $_site,
        $_sendFform;

    public function __construct(as_Sites $site, as_Subscribes $subscribe) {

        $this->_session = RegistrySession::instance();
        $this->mapper = new as_XmlMapper( General::$loadedModules['AutoSubmitter']->pathModule . 'rules/' . $site->host . '.xml');

        $curl = new Curl();
        $opt = array (CURLOPT_HEADER => true,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_TIMEOUT => 18,
                      CURLOPT_CONNECTTIMEOUT => 15,
                      CURLOPT_ENCODING => 'gzip,deflate',
                 //     CURLOPT_COOKIEFILE => General::$loadedModules['AutoSubmitter']->pathModule . 'cookies/'. $site->host.'_' . $subscribe->id . '.txt',
                //      CURLOPT_COOKIEJAR =>  General::$loadedModules['AutoSubmitter']->pathModule . 'cookies/'. $site->host.'_' . $subscribe->id . '.txt',
                      );
        $curl->setOptArray($opt);
        $curl->setResponseCharset($this->mapper->getEncoding());
        $this->_curl = $curl;


        $this->_fieldsSend = $this->mapper->getFields();

        //ставим отметку о том что начали обработку сайта для кокретной рассылки
        $subscribeSite = new as_SubscribesSites($subscribe->id, $site->id);
        $subscribeSite->status = 'Busy';
        $subscribeSite->save();

        //сохраняем все в тельце
        $this->_subscribeSites = $subscribeSite;
        $this->_subscribe = $subscribe;
        $this->_fieldsSend = $this->mapper->getFields();
        $this->_site = $site;
    }

    /**
     *
     */
    function _processingBefore() {
        $this->_ruleId;
        $before = $this->mapper->getBeforeActions();
        if ($before) {
            foreach($before as $action) {
             if ('request' == (string)$action['type']) {
                     $this->_curl->requestGet((string)$action['url']);
                     if ($this->_curl->getErrors()) {
                         return false;
                     }
                }
                if ('download' == (string)$action['type']) {
                    $url = (string)$action['url'];
                    $this->_curl->downloadFile(
                        $url,
                        DIR_PATH . '/img/downloads/captcha/'.$this->_site->host . '.gif');
                    if (!$this->_curl->getErrors()) {
                        $this->_fieldsSend[(string)$action['for']]['src'] = WEB_PREFIX.'Brill/img/downloads/captcha/'.$this->_site->host . '.gif';
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function _processingAfter() {
        $after = $this->mapper->getAfterActions();
        foreach($after as $action) {
            if ('find' == (string)$action['type']) {
                $find = trim((string)$action);
                $message = $action['message'];
                if (false === strpos($this->_curl->getResponseBody(), $find)) {
                    return new Error($message);
                }
            }
        }
        return true;
    }

    public function getForm() {
        return $this->_sendFform;
    }
    public function start($post = null) {

        if ($post) {

            $siteForm = new oFormExt();
            $siteForm->loadFromString($this->_subscribeSites->form);
            $siteValues = $siteForm->getFields();
            $newPost = array();
            foreach ($post as $k => $val) {
                foreach ($siteValues as $key => $value) {
                    if (isset($value['var']) && $value['var'] == $k) {
                        $newPost[$value['name']] = $val;
                        break 1;
                    }
                }
            }

            $siteForm->fill($newPost);
            if ($siteForm->isComplited()) {
                $this->mapper->fill($siteForm->getFields());
                $aHeaders = $this->mapper->getHeaders();
                $aGet = $this->mapper->getGet();
                $aPost = $this->mapper->getPost();
                $this->_curl->setHeaders($aHeaders);
                $this->_curl->setGet($aGet);
                $this->_curl->setPost($aPost);
                $url = $this->mapper->getUrlRule();
                $this->_curl->requestPost($url);
                
                $resultAfter = $this->_processingAfter();
                if ($resultAfter instanceof Error) {
                    $context = RegistryContext::instance();
                    $context->setError($resultAfter->message);
                    $this->_subscribeSites->status = 'Error';
                    $this->_subscribeSites->save();
                } else {
                    $this->_subscribeSites->status = 'Ok';
                    return true;
                }
            } else {
                return $siteForm;
            }
            //проверка формы и отсылка назад
            //иначе формирование данных для курла и отправка
            //проверка в полченных данных - ожидаемых
            //иначе выводи юзеру инфу - что этот сайт лаганул
        }
       // $this->_curl->resetCookies();
        $this->_curl->reset();
        if (!$this->_processingBefore()) {
            $context = RegistryContext::instance();
            $context->setError('Сайт не доступен');
        }
        $fields = $this->syncForm();
        $form = new oFormExt($this->_fieldsSend);
        $this->_subscribeSites->form = $form->getXmlAsText();
        $this->_subscribeSites->save();
        $this->_sendFform = $form;
        return $form;
       //echo 'start';die;
                /*
         * выполнение before функцикций
         * заполенение формы
         * отдаем форму пользователю
         * проверяем форму от пользователя
         * если все ок - отсылаем ее
         *
         * проверяем after
         *
         */

    }

    public function sendUserForm() {

    }

    function syncForm() {
        $form = new oFormExt();
        $form->loadFromString($this->_subscribe->form);
        $userFields = $form->getFields();

        foreach ($this->_fieldsSend as $key => &$value) {
            if (isset($value['source']) && isset($userFields[$value['source']]['value'])) {
                $value['value'] = $userFields[$value['source']]['value'];
            }
        }
    }

    function reSyncForm($form) {
        $siteFields = $form->getFields();
        foreach ($this->_fieldsSend as $key => &$value) {
            if (isset($userFields[$value['source']]['value'])) {
                $value['value'] = $siteFields[$value['source']]['value'];
            }
        }
    }
}

