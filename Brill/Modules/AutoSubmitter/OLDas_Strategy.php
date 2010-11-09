<?php
class as_Strategy {
    private $xml;
    private $_currentRule = 0;
    private $instruct;
    private $user;
    private $site;

    /**
     *
     * @param <type> $site_id
     * @return AS_xmlMapper
     */
function getInstruct($site_id) {}
    private $status = true;
    private $firstRule = 'Auth';

    public function  __construct(as_Sites $site, $user) {
       $this->xml = new as_XmlMapper($site->rule);
       $this->user = $user;
       $this->site = $site;
    }
    /**
     * Формирует строку гет запроса
     * @return string
     */
    private static function constructGet($array = array()) {
    }

    /**
     * Формирует пост
     * возвращает строку
     */
    private static function constructPOST($array = array()) {
    }

    /**
     * Формирует заголовки
     * возвращает массив
     * где ключ - название заголовка
     * а значение - значение заголовка
     * должно сооветствовать этому:
     * http://ru.wikipedia.org/wiki/%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA_%D0%B7%D0%B0%D0%B3%D0%BE%D0%BB%D0%BE%D0%B2%D0%BA%D0%BE%D0%B2_HTTP
     */
    private static function constructHeaders($array = array()) {
    }

    /**
     * дополнительный настройки для курла, например файлы...
     */
    private static function constructExtPOST($array = array()) {
    }

    /**
     * Методу передается имя сайта и он выполняет стандартную все инструкции
     * берет первый доступный юзеру сайт
     * @param string $siteHost если надо взять определенный сайт
     * @param string $rule  если надо выполнить только одно, конкретное правилоо
     */
    public function run($ruleId = 0) {
        if (!$ruleId) {
            $ruleId = $this->_currentRule;
        }
        $headers = $this->xml->getHeadersRule($ruleId);

        if($this->xml->isAjaxRule($ruleId)){
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

        if ($this->xml->getDynamicFields($ruleId)) {
            Bot::run(
                $this->xml->getActionRule($ruleId),
                $this->xml->getGetRule($ruleId),
                $this->xml->getPostRule($ruleId),
                $headers
            );
        }
            $aGet = $xml->getGet($ruleId);
            $aHeaders = $xml->getHeaders($ruleId);
            $response = as_Bot::run($aHeaders, $aGet, null, false);
            $dynFields = array();
            foreach ($xml->getDynamicFields as $field) {
                $dynFields[] = findDynamicField($response);

            }
        // если есть данные требующие заполнения пользователем и форма пользователя имеет пустые required поля
           if ($xml->getDynamicFields($ruleId)) {

//метод должен получить данные и вывести страницу пользователю
//должен быть счетчик попыток

                $html = AS_Bot::run(self::constructGet(), self::constructHeaders(), self::constructPOST());
                ParserHtml::getImage($html);
                PArserHtml::getSelect($html);
                 // идет ли запрос от пользователя на подтверждение или нет
                 //пока как заглушка
                 if (Subscribe::SubmitUser) {
                     $status->set(__CLASS__.' '.$host, 'waitingFields');
                 }
                 // должны добавить полученные данные на форму
              //waitingFields -- ожидание подтверждения от пользователя
                $context->set('UserForm', $userForm);
           } else if ($userForms->isComplited) {
                //отсылаем форму
               //узнает все ок или нет
               //если нет - отдает форму пользователю повторно и ошибкой
               //и ставим пометку что форма  -fail
               // по новой качаем картинку или что там у нас будет

               // если все ок ставим что руле прошли в базе
           }

    }

}