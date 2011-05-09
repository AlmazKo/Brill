<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cli
 *
 * @author almazko
 */
final class Cli {
    const ARG_HELP = 'h';
    const ARG_INFO = '-';
    private  static $_args;
    private  static $instance;
   
  public function getArgs() {
      return self::$_args;
  } 
  
  
  public function getArg($name) {
      if (!$this->hasArg($name)) {
          throw new Warning('Not found argument `' . $name . '`');
      }
      return self::$_args[$name];
  }
  
   public static function getStringForHelp($args, $prefix = '-', $encode = 'utf8') {
       $string = '';
       if (isset($args[self::ARG_INFO])) {
           $string .= $args[self::ARG_INFO] . "\n";
       }
       $string .= "Use the following arguments:\n";
       foreach ($args as $key => $value) {
           if (self::ARG_INFO != $key) {
               $string .= str_pad($prefix . $key, 8, " ", STR_PAD_LEFT) . "\t" . $value . "\n";
           }
           
       } 
       return $string;
    }
    
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        } 
        return self::$instance;
    }
    
    protected function __construct() {
        $request = RegistryRequest::instance();
        $this->setArgs($request->get('argv'));
    }
    
    private function setArgs (array $args) {
       if (is_null(self::$_args)) {
           foreach ($args as $key => $value) {
              if (preg_match('/(?:\-([^=]))(?:=(.*))?/i', $value, $matches)) {
                  self::$_args[$matches[1]] = isset($matches[2]) ? $matches[2] : null;
              }
           }
           return true;
       } else {
           return false;
       }
   }
   
   public function hasArg($key) {
       if (array_key_exists($key, self::$_args)) {
           return true;
       } else {
           return false;
       }
   } 
}
