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
    function getHeaders($rule = 0){

    }
    function getPost($rule = 0) {

    }
    function getGet($rule = 0) {

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
                 if ('true' == $field['form']) {
                    $aFields[(string)$field['htmlname']] = array('title' => (string)$field['htmlname'], 'value' => '', 'type' => (string)$field['htmltype'], 'required' => ('false' == (string)$field['required']) ? false : true);
                    if (isset($field['analog'])) {
                        $aFields[(string)$field['htmlname']]['analog'] = (string)$field['analog'];
                    }
                    if (isset($field['attr'])) {
                        $aFields[(string)$field['htmlname']]['attr'] = (string)$field['attr'];
                    }
                    if (isset($field['src'])) {
                        $aFields[(string)$field['htmlname']]['src'] = (string)$field['src'];
                    }
                 }
             }
         }
         return $aFields;
    }

}