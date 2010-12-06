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
                     // CURLOPT_COOKIE  => true,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_TIMEOUT => 8,
                      CURLOPT_CONNECTTIMEOUT => 15,
                      CURLOPT_ENCODING => 'gzip,deflate',
                      CURLOPT_COOKIEFILE => General::$loadedModules['AutoSubmitter']->pathModule . 'cookies/'. $site->host.'_' . $subscribe->id . '.txt',
                      CURLOPT_COOKIEJAR =>  General::$loadedModules['AutoSubmitter']->pathModule . 'cookies/'. $site->host.'_' . $subscribe->id . '.txt',
                      );
        $curl->setOptArray($opt);
        $curl->setCharsetResponse($this->mapper->getEncoding());
        $this->_curl = $curl;

        
   
        $subscribeSite = new as_SubscribesSites($subscribe->id, $site->id);

        $subscribeSite->status = 'Busy';
             Log::dump($subscribeSite->getValues());
        $subscribeSite->save();
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
                     $this->_curl->requestGet((string)$action['url'])->getResponseBody();
                     if ($this->_curl->getErrors()) {
                         return false;
                     }
                }
                if ('download' == (string)$action['type']) {
                    $url = (string)$action['url'];
                    $data[$action['name']] = $this->_curl->downloadFile(
                        $url,
                        DIR_PATH . '/img/downloads/captcha/'.$this->_site->host . '.gif');
                    if (!$this->_curl->getErrors()) {
                        $this->_fieldsSend[(string)$action['htmlname']]['src'] = WEB_PREFIX.'Brill/img/downloads/captcha/'.$this->_site->host . '.gif';
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function processingAfter() {
        $this->_ruleId;
    }

    public function getForm() {
        return $this->_sendFform;
    }
    public function start($post = null) {
//        if ($this->_session->is('stepSubscribe')) {
//            $this->_ruleId = $this->session->get('stepSubscribe');
//        }

        if ($post) {
            $siteForm = new oFormExt();
            $siteForm->loadFromString($this->_subscribeSites->form);
            $siteForm->fill($_POST);
            if ($siteForm->isComplited()) {
                $this->mapper->fill($siteForm->getFields());
                $aHeaders = $this->mapper->getHeaders();
                $aGet = $this->mapper->getGet();
                $aPost = $this->mapper->getPost();
                $this->_curl->setHeaders($aHeaders);
                $this->_curl->setGet($aGet);
                $this->_curl->setPost($aPost);

                $url = $this->mapper->getActionRule();
                $r= $this->_curl->requestPost($url)->getResponseBody();
                Log::dump($r);
                 Log::dump($this->_curl->getinfo());
                die( 'Форма успешно заполнена');
                
            } else {
                return $siteForm;
            }
            //проверка формы и отсылка назад
            //иначе формирование данных для курла и отправка
            //проверка в полченных данных - ожидаемых
            //иначе выводи юзеру инфу - что этот сайт лаганул
        }
        $this->_curl->resetCookies();
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
        
//        $mapper->getActionRule();
//        $before = $mapper->getBeforeActions();
//        $host = $mapper->getHost();
//        if ($this->session->is('subscribeForm')) {
//            $gets = $mapper->getGet();
//            $posts = $mapper->getPost();
//            $headers = $mapper->getHeaders();
//        } else {
//            $fields = $mapper->getFields();
//            if ($before) {
//                foreach($before as $action) {
//                    if ('request' == (string)$action['type']) {
//                         $response = $curl->requestGet((string)$action['url'])->getResponseBody();
//                    }
//                    if ('download' == (string)$action['type']) {
//                        $url = (string)$action['url'];
//                        $data[$action['name']] = $curl->downloadFile(
//                            $url,
//                            DIR_PATH . '/img/downloads/capthca/'.$site->host . '.gif');
//                        if (!$curl->getErrors()) {
//                            $fields[(string)$action['htmlname']]['src'] = WEB_PREFIX.'Brill/img/downloads/'.$site->host . '.gif';
//                        } else {
//                            Log::dump($curl->getErrors());
//                        }
//                    }
//                }
//            }
//            $form = new oForm($fields);
//            $form->setHtmlAfter($mapper->getAfterHtml());
//            $form->setHtmlBefore('Форма для сайта www.press-release.ru');
//            echo $form->buildHtml();
//            $this->session->set('subscribeForm', $form);
//        }
    }

    public function sendUserForm() {
        
    }

    function syncForm() {
        $form = new oFormExt();
        $form->loadFromString($this->_subscribe->form);
        $userFields = $form->getFields();

        foreach ($this->_fieldsSend as $key => &$value) {
            if(isset($value['analog'])) {
                if (isset($userFields[$value['analog']]['value'])) {
                    $value['value'] = $userFields[$value['analog']]['value'];
                }
            }
        }
    }

    function reSyncForm($form) {
        $siteFields = $form->getFields();
        foreach ($this->_fieldsSend as $key => &$value) {
            if(isset($value['analog'])) {
                if (isset($userFields[$value['analog']]['value'])) {
                    $value['value'] = $userFields[$value['analog']]['value'];
                }
            }
        }
    }
}

