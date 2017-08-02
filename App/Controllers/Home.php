<?php

namespace App\Controllers;

use App\Auth;
use App\Mail;
use Core\Controller;
use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html', [
            'user' => Auth::getUser()
        ]);
    }

}
