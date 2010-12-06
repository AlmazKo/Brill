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
    function getRule($ruleId = 0) {
        return $this->_sxe->rule[$ruleId];
    }

    function hasRule($ruleId) {
        if (isset($this->_sxe->rule[$ruleId])) {
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
                if (isset($field->data)) {
                    $post[$aField['name']] = (string)$field->data;
                }
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

    function getActionRule($ruleId = 0) {
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            return $rule->action['url'];
        }
    }
    //$fields['interface'] = array('title' => 'Cетевой интерфейс', 'value' => '', 'type'=>'text', 'required' => true, 'validator' => null, 'info'=>'Может быть именем интерфейса, IP адресом или именем хоста', 'error' => false, 'attr' => '', $checked = array());
    function getFields($ruleId = 0) {
        $aFields = array();
        if ($this->hasRule($ruleId)) {
            $rule = $this->getRule($ruleId);
            
            foreach ($rule->post->field as $field) {
                $aField = current($field);

                if ('true' == $field['form']) {

                    $name = (string)$field['name'];
                    $aFields[$name] = array();
                    foreach ($aField as $key => $value) {
                        switch((string)$value) {
                        case 'htmlname':
                            continue;
                        case 'required':
                            $aFields[$name][$key] = ('false' == (string)$value) ? false : true;
                            break;
                        default:
                            $aFields[$name][$key] = (string)$value;
                        }
                    }
                    if (!isset($aFields[$name]['value'])) {
                        $aFields[$name]['value'] = '';
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
          // Log::dump($fields);
            foreach ($rule->post->field as $field) {
                $aField = &current($field);
                if (isset($aField['name']) && isset($fields[$aField['name']])) {
                  //  Log::dump($fields[$aField['name']] .' - '.$fields[$aField['name']]['value']);
                    $field->addChild('data', $fields[$aField['name']]['value']);
                    //$aField->addAttribute('value', $fields[$aField['name']]['value']);
                } else {
                    $field->addChild('data', $aField['value']);
                }
            }
        }
    }
}