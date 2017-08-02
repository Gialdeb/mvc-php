<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 29/07/2017
 * Time: 17.31
 */

namespace App\Controllers;

use App\Models\User;
use \Core\View;
use \Core\Controller;


class Signup extends Controller
{
    /**
     * Show the accedi page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }

    /**
     * Accesso per un nuovo utente
     *
     * @return void
     */
    public function createAction()
    {
//        var_dump($_POST);
        $user = new User($_POST);
//        var_dump( $user);

        if($user->save()){

          $this->redirect('/signup/success');

        } else {

            // mostrare gli errori

            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);

//            var_dump($user->errors);
        }


    }

    /**
     * Mostra il Login se si è avuto successo
     *
     * @return void
     */
    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }


}