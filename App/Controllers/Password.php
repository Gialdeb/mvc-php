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

        $token_url = $this->getUserOrExit($token);

//        $user = User::findByPasswordReset($token);

            View::renderTemplate('Password/reset.html',[
                'token' => $token_url
            ]);

    }

    /**
     * Reset della password utente
     *
     * @return void
     */
    public function resetPasswordAction()
    {
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

         if($user->resetPassword($_POST['password'])){

             echo "Password Valida";
//             View::renderTemplate('Password/reset_success.html');

         } else {

             View::renderTemplate('Password/reset.html',[
                 'token' => $token,
                 'user'  => $user
             ]);

         }

    }

    /**
     * Cerca l'utente dal model con la pwd del token o finisci la richiesta tramite un mess
     *
     * @param string $token Password reset token inviato all'utente
     *
     * @return mixed User object se è stato trovato il token e non è scaduto, altrimenti null
     */

    protected function getUserOrExit($token)
    {
        $user = User::findByPasswordReset($token);

        if($user){

            return $user;

        } else {

            View::renderTemplate('Password/token_expired.html');
            exit;
        }

    }
    
}