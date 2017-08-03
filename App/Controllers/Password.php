<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 02/08/17
 * Time: 11:31
 */

namespace App\Controllers;


use App\Models\User;
use Core\Controller;
use Core\View;

class Password extends Controller
{
    /**
     * Mostro la pagina se si è persa la password
     *
     * @return void
     */
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.html');
    }

    /**
     * Invio link reset password per email
     *
     * @return void
     */
    public function requestResetAction()
    {
        User::sendPasswordReset($_POST['email']);

        View::renderTemplate('Password/reset_requested.html');
    }

    /**
     * Mostro il reset della password
     *
     * @return void
     */

    public function resetAction()
    {
        $token = $this->route_params['token'];

        $user = User::findByPasswordReset($token);

//        var_dump($user);
        if($user){

            View::renderTemplate('Password/reset.html');
        } else {

            echo "password reset Token non valido!";
        }
    }
    
}