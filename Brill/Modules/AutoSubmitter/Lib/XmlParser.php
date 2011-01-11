<?php
/**
 * Description of XmlParser
 *
 * @author Alexander
 */
class XmlParser {
    public  $_sxe = NULL;

    function  __construct($fileXml) {
        $this->_sxe = simplexml_load_file($fileXml);
    }
    function xmlOpen($fileXml){
        $this->_sxe = simplexml_load_file($fileXml);
    }
    function xmlClean(){
        $this->_sxe = NULL;
    }
    function xmlSave(){}
    function xmlLoadFromFile($file){}
    function xmlSaveToFile($file){}
}
