<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YandexXmlException
 *
 * @author almazko
 */
class GoogleXmlException extends Exception {
    protected $_urls;
    protected $_page;
   
    public function __construct(array $urls, $page, $message, $code, $previous) {
        $this->urls = $urlsl;
        $this->page = $page;
        parent::__construct($message, $code, $previous);
    }
    
    function getUrls() {
        return $this->_urls;
    }
    
    function getCurrentPage() {
        return $this->_page;
    }
}
