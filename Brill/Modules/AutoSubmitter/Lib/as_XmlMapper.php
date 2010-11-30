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

}