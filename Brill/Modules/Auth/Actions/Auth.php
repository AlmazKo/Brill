 <?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author Alexander
 */
class Auth {
    protected $fields;
    // act_login
    // act_Logout
    function act_Login() {

        if ($request->get('POST')) {

            if($post['login'] && $post['password']) {
                //$user->fill(query($post['login'], $post['password'])
                      //  oForm('error')
            }
        } else {
            oForm();
        }
        //берет данные из post и сессии
        //User->get
        //session ok
        //session false

    }

    function act_Logout() {
        // берет данны из пост и сессии
    }

}
