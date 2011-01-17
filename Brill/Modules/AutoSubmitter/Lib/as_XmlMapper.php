<?php
/**
 * Description of as_XmlMapper
 *
 * @author Alexander
 */
class as_XmlMapper extends XmlParser{
    public
        $_currentRule = 0,
        $_sxe;

    function  __construct($fileXml) {
        parent::__construct($fileXml);
        if (!isset($this->_sxe->rule) || !count($this->_sxe->rule)){
            Log::warning('Не были найдены правила в конфигурации '.$fileXml);
        }
    }
    /**
     * Получить набор действий, которые должны выполниться перед основным действием
     * @param int $ruleId
     * @return <type>
     */
    function getBeforeActions($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
           $rule = $this->getRule($ruleId);
           if (isset($rule->before)) {
               return $rule->before->action;
           } else {
                return false;
           }
        } else {
            return null;
        }
    }

    /**
     * Получить набор действий, которые должны выполниться после основного действия
     * @param int $ruleId
     * @return <type>
     */
    function getAfterActions($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
           $rule = $this->getRule($ruleId);
           if (isset($rule->after)) {
               return $rule->after->action;
           } else {
                return false;
           }
        } else {
            return null;
        }
    }

    function getRule($ruleId = 0) {
        return $this->_sxe->rule[(int)$ruleId];
    }

    function hasRule($ruleId) {
        if (isset($this->_sxe->rule[(int)$ruleId])) {
            return true;
        } else {
            return false;
        }
    }
    function getUrlAction($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
            return (string)$this->getRule($ruleId)->action['url'];
        }
    }

    /**
     * Являетли правило, автоматически выполняемым
     * @param unt $ruleId
     * @return bool 
     */
    function isAutoRule($ruleId = 0) {
        if ($this->hasRule($ruleId)) { 
            $rule = $this->getRule($ruleId);
            if ($rule['auto'] && 'true' == (string)$rule['auto']) {
                return true;
            }
        }
        return false;
    }
    function getHost() {
        return (string)$this->_sxe['host'];
    }
    /**
     * получить кодировку сайта
     * @return string
     */
    function getEncoding() {
        return (string)$this->_sxe['encoding'];
    }
    function getHeaders($ruleId = 0){
        $aHeaders = array();
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            foreach ($rule->headers->field as $field) {
                $aField = &current($field);
                if (isset($field->data)) {
                    $aHeaders[$aField['name']] = (string)$field->data;
                }
            }
        }
        return $aHeaders;
    }
    function getPost($ruleId = 0) {
        $post = array();
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            foreach ($rule->post->field as $field) {
                $aField = &current($field);
                $post[$aField['name']] = (string)$field;
            }
        }
        return $post;
    }
    function getGet($ruleId = 0) {
        $get = array();
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            foreach ($rule->get->field as $field) {
                $aField = &current($field);
                if (isset($field->data)) {
                    $get[$aField['name']] = (string)$field->data;
                }
            }
        }
        return $get;
    }
    function getInfo($rule = 0) {

    }

    function getUrlRule($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            return $rule['url'];
        }
    }

    /**
     * Получает способ используемый для отправки данных на сайт
     * @param int $ruleId
     * @return <type>
     */
    function getFormEnctypeRule($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            if (isset($rule['enctype'])) {
                return $rule['enctype'];
            } else {
                return false;
            }
            
        }
    }

    /**
     * Получить "публичные поля" - поля которые необходимо получить от пользователя
     *
     * @param int $ruleId
     * @return string
     */
    function getPublicFields($ruleId = 0) {
        $aFields = array();
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            foreach ($rule->post->field as $field) {
                $aField = current($field);
                if ('true' == $field['form']) {
                     
                    $name = (string)$field['var'];
                   
                    $aFields[$name] = array();
                    foreach ($aField as $key => $value) {
                        switch($key) {
                        // Служебные поля name, source не сохраняем
                        case 'name':
                        case 'source':
                        case 'sourcesite':
                            continue;
                        case 'var':
                            $aFields[$name]['name'] = $value;
                            break;
                        case 'required':
                            $aFields[$name][$key] = ('false' == $value) ? false : true;
                            break;
                        case 'data':
                            $aFields[$name][$key] = unserialize($value);
                            break;
                        default:
                            $aFields[$name][$key] = $value;
                        }
                    }
                    //поле важное для объектов типа oForm, поэтому если не нашли его - все равно создаем
                    if (!isset($aFields[$name]['value'])) {
                        $aFields[$name]['value'] = (string)$field;
                    }
                }
             }
         }
         return $aFields;
    }

    public function getBeforeHtml($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            return (string)$rule->beforeHtml;
        }
    }

    public function getAfterHtml($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            return (string)$rule->afterHtml;
        }
    }

    /**
     * Заполняет форму пользовательскими данными
     * @param <type> $fields
     * @param int $ruleId
     */
    public function fill($fields, $ruleId = 0) {
         if ($this->hasRule($ruleId)) { 
            $rule = $this->getRule($ruleId);
            foreach ($rule->post->field as $field) {
                $aField = &current($field); 
                if (isset($aField['var']) && isset($fields[$aField['var']]) && 'true' == $aField['form']) {
                    $value = isset($fields[$aField['var']]['value']) ? $fields[$aField['var']]['value'] : '';
                    $value = htmlspecialchars($value, ENT_QUOTES, ENCODING_CODE);
                    $field[0] = $value;
                }
            }
        }
    }

    /**
     * Заполняет форму из внешнего источника
     * @param <type> $fields
     * @param int $ruleId
     */
    public function fillOut($fields, $ruleId = 0) { 
         if ($this->hasRule($ruleId)) { 
            $rule = $this->getRule($ruleId);
            foreach ($rule->post->field as $field) { 
                $aField = &current($field); echo '<hr>--'.$aField['name'];
                if (isset($aField['out']) && 'true' == $aField['out'] && isset($fields[$aField['name']]) ) {
                    $value = $fields[$aField['name']];
                    if ($aField['type'] == 'select') {
                        $field->addAttribute('data', serialize($value));
                    } else {
                        $value = htmlspecialchars($value, ENT_QUOTES, ENCODING_CODE);
                        $field[0] = $value;
                    }
                }
            }
        }
    }
}
