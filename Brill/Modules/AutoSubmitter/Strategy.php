<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Strategy {
    public $status = null;
    public $end = null;
    public $data = null;


    private $currentRule;
    private $obj_AS_xmlMapper = null;
    private $obj_AS_Bot = null;

    function  __construct(UserDataProject $obj_UserDataProject, AS_site $obj_AS_site) {
        $this->obj_AS_xmlMapper = new AS_xmlMapper($obj_UserDataProject, $obj_AS_site);
        $this->obj_AS_Bot = new AS_Bot();
        //print_r($this->obj_AS_xmlMapper->getPostRule(2));
        //print_r($this->obj_AS_xmlMapper->getGetRule(2));
        //print_r($this->obj_AS_xmlMapper->getDynamicFieldsRule(2));
        //print_r($this->obj_AS_xmlMapper->getHeadersRule(1));
        //print_r($this->obj_AS_xmlMapper->getActionRule(1));
        //print_r($this->obj_AS_xmlMapper->getResponseRule(1));
        //print_r($this->obj_AS_xmlMapper->listRules());

    }

    public function work($data = null){
    /*
     * логика работы:
     * 1) получаем список правил ListRules
     *      1.1) смотрим getDynamicFieldsRule(id_rule)
     *           1.1.1) если таковые есть:просим BOT`a запросить страницу. Из этой страницы дергаем то
     *                  что нужно для заполнения Динамичного поля
     *                  1.1.1.1) если сервер отдал не 200 ok, то BOT облажался - сообщаем фигвам выполнения
     *           1.1.2) Если нет, то идем на 1.2
     *      1.2) делаем предотправочное view для данного правила с возможностью редактирования
     *      1.3) ждем нажатия "submit", вызываем BOT`a чтоб отправил все что заполнил пользователь
     *          1.3.1) если сервер отдал не 200 ok, то BOT облажался - сообщаем фигвам выполнения
     *      1.4) смотрим результат соответствию getResponseRule(id_rule) и просим BOT`a
     *          1.4.1) если соответствует, то говорим пользователю что все гуд
     *          1.4.2) если не соответствует, то говорим ПОЛЬЗОВАТЕЛЮ что фигвам и предлагаем попробывать еще раз
     *
     *
     *
     * сделаем все без динамик фиелдс, ибо запутаться с ними можно
     */
        //задаем начальное правило
        if (!$this->currentRule){
            $this->currentRule = 1;
        }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //подготавливаем заголовки для бота
        $headers = array();
        $headers = $this->obj_AS_xmlMapper->getHeadersRule($this->currentRule);
        if($this->obj_AS_xmlMapper->isAjaxRule($this->currentRule)){
            //если аякс
            if (is_array($headers)){
                //если массив заголовков
                if (!key_exists('content-type', $headers)){
                    $headers['content-type'] = "application/x-www-form-urlencoded; charset=UTF-8;";
                }
                if (!key_exists('x-requested-with', $headers)){
                    $headers['x-requested-with'] = "XMLHttpRequest";
                }
            }else{
                $headers['content-type'] = "application/x-www-form-urlencoded; charset=UTF-8;";
                $headers['x-requested-with'] = "XMLHttpRequest";
            }
        }
        //посылаем боту запрос cо всеми готовыми данными для правила
        $this->obj_AS_Bot->RUN(
                $this->obj_AS_xmlMapper->getActionRule($this->currentRule),
                $this->obj_AS_xmlMapper->getGetRule($this->currentRule),
                $this->obj_AS_xmlMapper->getPostRule($this->currentRule),
                $headers
                );
        //получаем правильный респонс
        $good_response = $this->obj_AS_xmlMapper->getResponseRule($this->currentRule);
        //проверяем выполнено ли правило
        if ($this->isResponse($good_response['url'], $good_response['value'])){
            //смотрим финиш выполнения если правило выполнено правильно
            if (!key_exists($this->currentRule + 1,  $this->obj_AS_xmlMapper->listRules())){
               $this->end = '1';
               $this->status = 'RulesEND';
            }else{
               $this->end = null;
               $this->status = 'Next';
            }
        }else{
            $this->status = 'Error';
        }

/*
        $DynamicFieldsRule = $this->obj_AS_xmlMapper->getDynamicFieldsRule($this->currentRule);
        //print_r($DynamicFieldsRule);
        if ($DynamicFieldsRule){
            //нулевые поля, нужно их заполнить - направляем запрос к боту
            $this->status = 'DynamicFieldsRule';
            $this->data = $DynamicFieldsRule;
        }else{
            //нулевых полей нет, поэтому выполняем само правило - направляем запрос к боту
            $this->status = 'Next';
        }
 *
 */



        // в зависимости от статуса стратегии мы решаем что возвращать в action
        switch ($this->status) {
            case 'Next':
                    $this->currentRule++;
                    $this->data = 'переходим на следующее правило<br />';
                break;
            case 'DynamicFieldsRule':
                    $this->data = 'нужно попросить пользователя заполнить данные<br />';
                break;
            case 'RulesEND':
                    $this->data = 'мы закончили выполнять все правила';
                    $this->end = 'YES';
                break;
            case 'Error':
                    //$this->data = 'правило не выполнено, предлагаем попробывать снова';
                    $this->data = '';
                    $this->end = 'NO';
                break;
        }
        return $this->data;
    }

    //проверяем соответствует ли ответ тому чего мы хотели получить(регулярками вхождения строк в исходный текст)
    //$str - строка которую нужно посмотреть в исходном коде
    //$sourceHTML - исходный код возвращенной страницы
    //возвращает true или false
    public function isResponse($url = null, $str = null, $send_param_get = null, $send_param_post = null, $headers = null){
        if (!empty($url)){
            $this->obj_AS_Bot->RUN(
                    $url,
                    $send_param_get,
                    $send_param_post,
                    $headers
                    );
            if ($this->obj_AS_Bot->GetResponseHTMLsource()){
                if (@substr_count($this->obj_AS_Bot->GetResponseHTMLsource(), $str) || empty($str)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}