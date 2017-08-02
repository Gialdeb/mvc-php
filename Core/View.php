<?php

namespace Core;
use \App\Auth;
use \App\Flash;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig_Environment($loader);
            //aggiungo la variabile globale di Sessione
            //$twig->addGlobal('session', $_SESSION);
            //Aggiungo a Twig is_logged_in per verificare se l'utente è loggato
            //$twig->addGlobal('is_logged_in', Auth::isLoggedIn());
            // Vedo chi è l'utente attualmente loggato
            $twig->addGlobal('current_user', Auth::getUser());
            // Setto la variabile globale per mostrare i messaggi nella sessione
            $twig->addGlobal('flash_messages', Flash::getMessage());
        }

        echo $twig->render($template, $args);
    }
}
