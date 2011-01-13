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
        $_site,
        // форма распарсенная с сайта
        $_formSite;

    public function __construct(as_Sites $site, as_Subscribes $subscribe, as_SitesUsers $sitesUsers = null) {

        /*
         * 1. парсим rule
         * 2. парсим SitesUsers
         * 3. сливаем всю информацию в mapper
         * 4. пустая ли инфа у юзера?.
         * 4.1. получаем данные из рассылки
         * 4.2 сливаем эти данные в mapper
         * 4.3 формируем из mapper UserForm
         * 5 у юзера уже есть инфа - ничего не делаем
         */


        /*
         * START
         *
         * 1. есть ли данные из поста
         * 1.1 заполняем UserForm
         * 1.2 проверяем заполненность. иначе сливаем экшену с ошибками
         * 1.3 сливаем данные в mapper
         * 1.4 проверяем маппер, если ошибки, заново мержим userform c с mapperом и отдаем юзеру(может происходить, если юзер колодовал с формой или был изменен сам маппер)
         * 1.5 выполняем правило
         * 1.6 опрошиваем курла, все ли нормально, если что сливаем все как есть юзеру и пишем ошибку в лог
         * 1.7 если да выполняем опрос курла( был ли ридирект, найдена ли фраза и т.д.). если что отадаем юзеру
         * 2. нет поста
         * 2.1 инициализируем курл
         * 2.2 выполняем подготовительные данные
         * 2.3 получаем UserForm
         * 2.4 сливаем полученные данные в UserForm. может быть еще в mapper
         
         */



         /*
          * SiteRuleForm - форма для отправки текущему сайту, с
          * SubscribeForm - форма-шаблон для сайта, используется только в начале
          * UserForm - это упрощенная форма SiteRuleForm, которая содержит только ее публичные поля
          * SiteUserSettings - настройки для сайта у юзера
          */

        
        // Прослойка для связи с маппером
        $this->mapper = new as_XmlMapper(General::$loadedModules['AutoSubmitter']->pathModule . 'rules/' . $site->host . '.xml');
        /*
         * должно происходить сохранение личных данных пользователя SiteRuleForm
         */
        $subscribeSite = new as_SubscribesSites(array($subscribe->id, $site->id));
        $this->_ruleId = $subscribeSite->rule_num;
        //если первый запуск - тогда берем данные из Subscribe
        if (!$subscribeSite->form) {
            $subscribeForm = new oFormExt();
            $subscribeForm->loadFromString($subscribe->form);
            $this->mapper->fill($subscribeForm->getFields(), $this->_ruleId);
        } else {
            $userForm = new oFormExt();
            $userForm->loadFromString($subscribeSite->form);
            $this->mapper->fill($userForm->getFields(), $this->_ruleId);
        }
        if ($sitesUsers) {
            echo '<hr>';
            $userFields['login'] = array('value' => $sitesUsers->login);
            $userFields['password'] = array('value' => $sitesUsers->password);
            $this->mapper->fill($userFields, $this->_ruleId);
        }
        $fields = $this->mapper->getPublicFields($this->_ruleId);
        $userForm = new oFormExt($fields);

        $subscribeSite->form = $userForm->getXmlAsText();

        $subscribeSite->status = 'Busy';
        $subscribeSite->save();
        
        $curl = new Curl();
        $opt = array (CURLOPT_HEADER => true,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_FOLLOWLOCATION => false,
                      CURLOPT_TIMEOUT => 20,
                      CURLOPT_CONNECTTIMEOUT => 7,
                     );

        $curl->setOptArray($opt);
        $curl->setResponseCharset($this->mapper->getEncoding());
        $curl->setFormEnctype($this->mapper->getFormEnctypeRule($this->_ruleId));
        $this->_curl = $curl;
        $this->_userForm = $userForm;
        $this->_site = $site;
        $this->_subscribeSite = $subscribeSite;
    }

    /**
     * выполнение необходимых операций перед запуском основной программы текущего правила
     */
    function _processingBefore() {
        $before = $this->mapper->getBeforeActions($this->_ruleId);
        if ($before) {
            foreach($before as $action) {
                switch((string)$action['type']) {
                    case 'request':
                        $this->_curl->requestGet((string)$action['url']);
                        if ($this->_curl->getErrors()) {
                            $this->_subscribeSite->rule_num = 0;
                            $this->_subscribeSite->save();
                            return new Error('Не удалось выполнить запрос к серверу');
                        }
                        break;
                    case 'find':
                        $find = trim((string)$action);
                        $message = $action['message'];
                        if (false === strpos($this->_curl->getResponseBody(), $find)) {
                            if ('next' == (string)$action['isFail']) {
                                return false;
                            }
                        }
                    break;
                    
                    case 'download':
                        $url = (string)$action['url'];
                        $this->_curl->downloadFile(
                            $url,
                            DIR_PATH . '/img/downloads/captcha/'.$this->_site->host . '.gif');
                        if (!$this->_curl->getErrors()) {
                            if ($this->_userForm->isField((string)$action['for'])) {
                                $this->_userForm->setFieldAttr('captcha', 'src', WEB_PREFIX.'Brill/img/downloads/captcha/'.$this->_site->host . '.gif');
                            }
                        } else {
                            return new Error('Не удалось скачать каптчу с сервера');
                        }
                        break;

                    case 'parseform':
                        $nameForm = null;
                        if (isset($action['name_form'])) {
                            $nameForm = (string)$action['name_form'];
                        }
                       $html = $this->_curl->getResponseBody();
                       
                       $dom = new DomExt($html);
                       $this->_formSite = $dom->parseForm($nameForm);
                       Log::dump($this->_formSite);
                    break;
                }
            }
        }
        return true;
    }

    function _processingAfter() {
        $after = $this->mapper->getAfterActions($this->_ruleId);
        foreach($after as $action) {
            if ('find' == (string)$action['type']) {
                $find = trim((string)$action);
                $message = $action['message'];
                if (false === strpos($this->_curl->getResponseBody(), $find)) {
                    $this->_subscribeSite->rule_num = 0;
                    $this->_subscribeSite->save();
                    return new Error($message);
                }
            }
        }
        return true;
    }

    public function getForm() {
        return $this->_userForm;
    }
    
    public function start($post = null) {
        if ($this->mapper->isAutoRule($this->_ruleId) || $post) {
            if (!$post) {
                echo '22Авто действие';
                $resultBefore = $this->_processingBefore();
                if ($resultBefore instanceof Error) {
                    $this->_subscribeSite->status = 'Error';
                    $this->_subscribeSite->save();
                    return $resultBefore;
                } if (!$resultBefore) {
                    $this->_ruleId++;
                    // выполнение основного действия правила можно пропустить
                    $this->_subscribeSite->rule_num = $this->_ruleId;
                    $fields = $this->mapper->getPublicFields($this->_ruleId);
                    $userForm = new oFormExt($fields);
                    $this->_subscribeSite->form = $userForm->getXmlAsText();
                    $this->_subscribeSite->save();
                    $this->_userForm = $userForm;
                    
                    return $this->start();
                }
                
            } else {
                $userForm = $this->_userForm;
                $userForm->fill($post);
                if (!$userForm->isComplited()) {
                    $this->_subscribeSite->form = $userForm->getXmlAsText();
                    $this->_subscribeSite->save();
                    return $userForm;
                }
                $this->mapper->fill($userForm->getFields());
            }
            $aHeaders = $this->mapper->getHeaders($this->_ruleId);
            $aGet = $this->mapper->getGet($this->_ruleId);
            $aPost = $this->mapper->getPost($this->_ruleId);
            $this->_curl->setHeaders($aHeaders);
            $this->_curl->setGet($aGet);
            $this->_curl->setPost($aPost);
            $url = $this->mapper->getUrlRule($this->_ruleId);
            $this->_curl->requestPost($url);
            if ($this->_curl->getErrors()) {
                $this->_subscribeSite->status = 'Error';
                $this->_subscribeSite->save();
                return new Error('Не удалось соединится с сервером');
            }
//            Log::dump($this->_curl->getResponseBody());

            $resultAfter = $this->_processingAfter();
            if ($resultAfter instanceof Error) {
                $this->_subscribeSite->status = 'Error';
                $this->_subscribeSite->save();

                //по новой собираем данные
                $resultBefore = $this->_processingBefore();
                if ($resultBefore instanceof Error) {
                    return $resultBefore;
                }
                $context = RegistryContext::instance();
                $context->setError($resultAfter);
                return $userForm;
            } else {
                $this->_ruleId++;
                if ($this->mapper->hasRule($this->_ruleId)) {
                    // есть еще правила - идем к ним
                    $this->_subscribeSite->rule_num = $this->_ruleId;
                    $fields = $this->mapper->getPublicFields($this->_ruleId);
                    $userForm = new oFormExt($fields);
                    $this->_subscribeSite->form = $userForm->getXmlAsText();
                    $this->_subscribeSite->save();
                    $this->_userForm = $userForm;
                    
                    return $this->start();
                } else {
                    // закончили по этому сайту
                    $this->_subscribeSite->form = '';
                    $this->_subscribeSite->status = 'Ok';
                    $this->_subscribeSite->save();
                    return true;
                }
            }
        }

//        if ($post) {
//            $userForm = $this->_userForm;
//            $userForm->fill($post);
//            if ($userForm->isComplited()) {
//
//                $this->mapper->fill($userForm->getFields());
//
//             //   Log::dump($this->mapper);
//                $aHeaders = $this->mapper->getHeaders();
//                $aGet = $this->mapper->getGet();
//                $aPost = $this->mapper->getPost();
//                $this->_curl->setHeaders($aHeaders);
//                $this->_curl->setGet($aGet);
//                $this->_curl->setPost($aPost);
//                $url = $this->mapper->getUrlRule();
//                $this->_curl->requestPost($url);
//               
//                if ($this->_curl->getErrors()) {
//                    $this->_subscribeSite->status = 'Error';
//                    $this->_subscribeSite->save();
//                    return new Error('Не удалось соединится с сервером');
//                }
//                $resultAfter = $this->_processingAfter();
//                if ($resultAfter instanceof Error) {
//                    $this->_subscribeSite->status = 'Error';
//                    $this->_subscribeSite->save();
//                    
//                    //по новой собираем данные
//                    $resultBefore = $this->_processingBefore();
//                    if ($resultBefore instanceof Error) {
//                        return $resultBefore;
//                    }
//                    $context = RegistryContext::instance();
//                    $context->setError($resultAfter);
//                    return $userForm;
//                } else {
//                    $this->_subscribeSite->form = '';
//                    $this->_subscribeSite->status = 'Ok';
//                    $this->_subscribeSite->save();
//                    return true;
//                }
//            } else {
//                $this->_subscribeSite->form = $userForm->getXmlAsText();
//                $this->_subscribeSite->save();
//                return $userForm;
//            }
//        }


       
        //обнуляем память курла
        //$this->_curl->reset();
        $resultBefore = $this->_processingBefore();
        if ($resultBefore instanceof Error) {
            $this->_subscribeSite->status = 'Error';
            $this->_subscribeSite->save();
            return $resultBefore;
        }
        return $this->_userForm;
    }

    public function sendUserForm() {

    }
}