<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 31/07/17
 * Time: 18:06
 */

namespace App\Controllers;


use Core\Controller;

abstract class Authenticated extends Controller
{
    /**
     * Richiedo l'accesso prima di ogni metodo presente nel Controller
     *
     * @return void
     */

    public function before()
    {
        $this->requireLogin();
    }
}