<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XmlParser
 *
 * @author Alexander
 */
class XmlParser {
    protected  $xml = NULL;

    function  __construct($fileXml) {
        $this->xml = simplexml_load_file($filexml);
    }
    function xmlOpen($filexml){
        $this->xml = simplexml_load_file($filexml);
    }
    function xmlClean(){
        $this->xml = NULL;
    }
    function xmlSave(){}
    function xmlLoadFromFile($file){}
    function xmlSaveToFile($file){}
}
