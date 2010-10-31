<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of userdata
 *
 * @author lubyagin segey
 */
class UserData {

    protected  $login = 'almaz';
    protected  $password = 'ALR210411AL';



    protected  $fam = 'fam';
    protected  $otch = 'otch';
    protected  $name = 'name';
    protected  $phone = 'телефон';
    protected  $facs = 'факс';
    protected  $email = 'емайл';
    protected  $site = 'сайт';
    protected  $address = 'адрес';
    protected  $firma = 'firma';


    function SetLogin($value){
        $this->login = $value;
    }
    function GetLogin(){
        return $this->login;
    }
    function SetPassword($value){
        $this->password = $value;
    }
    function GetPassword(){
        return $this->password;
    }



    function SetName($value){
        $this->name = $value;
    }
    function GetName(){
        return $this->name;
    }

    function SetFam($value){
        $this->fam = $value;
    }
    function GetFam(){
        return $this->fam;
    }

    function SetOtch($value){
        $this->otch = $value;

    }
    function GetOtch(){
        return $this->otch;
    }

    function SetPhone($value){
        $this->phone = $value;
    }
    function GetPhone(){
        return $this->phone;
    }

    function SetFacs($value){
        $this->facs = $value;
    }
    function GetFacs(){
        return $this->facs;
    }

    function SetEmail($value){
        $this->email = $value;
    }
    function GetEmail(){
        return $this->email;
    }

    function SetSite($value){
        $this->site = $value;
    }
    function GetSite(){
        return $this->site;
    }

    function SetAddress($value){
        $this->address = $value;
    }
    function GetAddress(){
        return $this->address;
    }

    function SetFirma($value){
        $this->firma = $value;
    }
    function GetFirma(){
        return $this->firma;
    }
}
?>
