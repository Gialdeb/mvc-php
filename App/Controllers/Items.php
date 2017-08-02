<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 31/07/17
 * Time: 16:17
 */

namespace App\Controllers;


use App\Auth;
use Core\Controller;
use Core\View;

class Items extends Authenticated
{
    /**
     *
     * Item index
     *
     * @return void
     */

    public function indexAction()
    {
        View::renderTemplate('Items/index.html');
    }

    /**
     *
     * aggiungo nuovo
     *
     * @return void
     */

    public function newAction()
    {
        echo "new action";
    }

    /**
     *
     * mostro nuovo
     *
     * @return void
     */

    public function showAction()
    {
        echo "show action";
    }


}