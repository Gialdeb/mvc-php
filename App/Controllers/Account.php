<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 30/07/2017
 * Time: 20.38
 */

namespace App\Controllers;

use \App\Models\User;
use Core\Controller;

/**
 * 
 * Account Controller
 * 
 */


class Account extends Controller
{
    /**
     *
     * Verifico se la email esiste già con la chiamata (AJAX)
     *
     * @return void
     */
    
    public function validateEmailAction()
    {
        $e_valido = ! User::emailExists($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($e_valido);
    }
    
}