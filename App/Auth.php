<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 31/07/17
 * Time: 15:42
 */

/**
 * Autenticazione
 */

namespace App;


use App\Models\RememberedLogin;
use App\Models\User;

class Auth
{
    /**
     * Autenticazione
     *
     * @param User $user Model
     * @param boolean $ricordami Ricordare l'utente se è true
     *
     *
     * @return void
     */
    public static function login($user, $ricordami)
    {
        //Se l'utente esiste cambio id della sessione
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;

        // Se true ricordo l'utente
        if ($ricordami){

            if($user->rememberLogin()){
                setcookie('ricordami', $user->remember_token, $user->expiry_timestamp, '/');

            }

        }
    }

    /**
     *
     * Logout utente
     *
     * @return void
     */
    public static function logout()
    {
        // Rimuovo tutte le sessioni
        $_SESSION = [];

        // Elimino i cookie dalla sessione
        if(ini_get('session.use_cookies')){
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Infine elimino la SESSIONE
        session_destroy();

        // elimino il cookie se presente
        static::forgetLogin();
    }

    /**
     *
     * Memorizzo la pagina originale richiesra nella sessione
     *
     * @return void
     */


    public static function rememberRequestPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    /**
     *
     * Eseguo la richiesta originale dopo il login, o di default sulla home page
     *
     * @return void
     */
    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/';
    }

    /**
     * Ritorno l'utente attualemnte loggato
     *
     * @return mixed User model o niente se non è loggato
     */

    public static function getUser()
    {
        if (isset($_SESSION['user_id'])){

            return User::findByID($_SESSION['user_id']);

        } else {

            return static::loginFromRememberCookie();

        }
    }


    /**
     * Login utente cookie set remember
     *
     * @return mixed User model se il cookie è settato o null
     */

    protected static function loginFromRememberCookie()
    {
        $cookie = $_COOKIE['ricordami'] ?? false;

        if($cookie){

            $remembered_login = RememberedLogin::findByToken($cookie);

            if ($remembered_login && ! $remembered_login->hasExpired()){

                $user = $remembered_login->getUser();

                static::login($user, false);

                return $user;

            }

        }
    }

    /**
     * Perdita del Login
     *
     * @return void
     */

    protected static function forgetLogin()
    {
        $cookie = $_COOKIE['ricordami'] ?? false;

        if($cookie){

            $remembered_login = RememberedLogin::findByToken($cookie);

            if($remembered_login){

                $remembered_login->delete();

            }

            setcookie('ricordami', '', time() -3600); // imposto come scaduto

        }
    }



}