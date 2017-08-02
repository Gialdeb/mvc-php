<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 31/07/2017
 * Time: 10.38
 */

namespace App\Controllers;


use App\Auth;
use App\Flash;
use App\Models\User;
use Core\Controller;
use Core\View;

class Login extends Controller
{
    /**
     *
     * Mosta la pagina di Login
     *
     */

    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    /**
     *
     * Accesso per l'utente
     *
     * @return void 
     */
    public function createAction()
    {
//        echo $_REQUEST['email'] . " , " . $_REQUEST['password'];
//        $user = User::findByEmail($_POST['email']);
//        var_dump($user);
        // Passo i valori che arrivano dal Form
        $user = User::authenticate($_POST['email'], $_POST['password']);

        // Verifico se è stato impostato il ricordami al login
        $ricordami = isset($_POST['ricordami']);

        if($user){

            Auth::login($user, $ricordami);

            // session_create_id(true);
            Flash::addMessage('Login avvenuto con successo');

           $this->redirect(Auth::getReturnToPage());

        } else {

            // Messaggio errore nella sessione se non si riesce ad effettuare il login
            Flash::addMessage('Login non avvenuto, riprovare di nuovo', Flash::WARNING);

            View::renderTemplate('Login/new.html', [
                    'email' => $_POST['email'],
                    'ricordami' => $ricordami
                ]);
        }
    }

    /**
     *
     * Sessione utente distrutta
     *
     * @return void
     */

    public function destroyAction(){

        Auth::logout();

        // chiamo la funzione mostraMessaggioLogoutAction(mostra-messaggio-logout)
        $this->redirect('/login/mostra-messaggio-logout');

    }

    /**
     * Funzione per mostrare il messaggio di uscita nella sessione
     * se inserisco il codice in destroyAction non visualizzo il messaggio perché
     * distruggo la sessione e quindi il messaggio non viene visualizzatp
     *
     * @return void
     */

    public function mostraMessaggioLogoutAction()
    {
        Flash::addMessage('Logout avvenuto con successo', Flash::SUCCESS);

        $this->redirect('/');
    }

}