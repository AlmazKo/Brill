<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Keywords
 *
 * @author almaz
 */
class Keyword {

    const STATUS_OK = 1;
    const STATUS_ERROR = 2;
    public $id;
    public $keyword;
    public $region;
    public $url;
    public $site;
    public $range;
    public $premiya;
    public $position = 0;
    public $foundByLink;
    public $detectedUrl = false;
    public $status = false;
    
    public function __toString() {
        return $this->keyword;
    }
    public function __construct(array $rawKeywords = array()) {
        $this->id       = isset($rawKeywords['kw_id'])      ? (int)$rawKeywords['kw_id'] : null;
        $this->keyword  = isset($rawKeywords['kw_keyword']) ? (string)$rawKeywords['kw_keyword'] : null;
        $this->url      = isset($rawKeywords['kw_url'])     ? (string)$rawKeywords['kw_url'] : null;
        $this->region   = isset($rawKeywords['rg_region'])  ? (int)$rawKeywords['rg_region'] : null;
        $this->range   = isset($rawKeywords['kw_range'])  ? (int)$rawKeywords['kw_range'] : null;
        $this->premiya   = isset($rawKeywords['kw_premiya'])  ? (int)$rawKeywords['kw_premiya'] : null;
        
        
    }
    
    public function __set($name, $value) {
        throw new Exception('Property `'. $name .'` doesn\'t exist');
    }
}
