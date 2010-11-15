<?
interface  IContext {
   function  __get($key);
   public function del($key) ;
   public function getTpl($key);
   public function setTpl($key, $nameTpl, $module = false);
   public function setTopTpl($nameTpl, $module = false);
   public function getTopTpl();
}