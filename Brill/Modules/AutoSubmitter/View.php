<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of View
 *
 * @author Sergey
 */
class View {
    public $stek = null;

    public function UserFormProject($value = null){
        $result = '<html><head>
                    <META http-equiv="content-type" content="text/html; charset=UTF8">
                    </head><body>';
        $result .= '<form action="action.php" method="POST">
                        <input type = "text" name="login" value = "login_blya"><br />
                        <input type = "text" name="password" value = "password_blya"><br />
                        <input type = "text" name="section" value = "section_blya"><br />
                        <input type = "text" name="code" value = "code_blya"><br />
                        <input type = "submit" value = "submit">
                    </form>';

        $result .= '</body></html>';
        echo $result;
    }

   public function PrintData(){
        $result = '<html><head>
                    <META http-equiv="content-type" content="text/html; charset=UTF8">
                    </head><body>';

        $result .= $this->stek;

        $result .= '</body></html>';

        echo $result;
    }


   public function Print_RData($value){
        echo '<html><head>
                <META http-equiv="content-type" content="text/html; charset=UTF8">
                </head><body>';
        print_r($value);

        echo '</body></html>';
    }

    public function FormRepeat(){
        $this->stek .= 'Ошибка! Попробывать еще раз?
                        <form method="GET"><input type="button" name="button_yes" value="YES"><input type="button" name="button_no" value="NO"></form>
                        ';
    }

    public function addStek($value){
        $this->stek .= $value . "<br />";
    }

}
?>
