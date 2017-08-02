<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 02/08/17
 * Time: 10:55
 */

namespace App;


use Mailgun\Mailgun;

class Mail
{
    /**
     * Invio messaggio
     *
     * @param string $to Destinatario
     * @param string $subject Oggetto del messaggio
     * @param string $text testo del messaggio contenuto
     * @param string $html contenuto del messaggio in HTML
     *
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {
        $mg = new Mailgun(Config::SECRET_API_KEY);
        $domain = Config::MAILGUN_DOMAIN;

        // composizione del messaggio
        $mg->sendMessage($domain, array(
            'from' => 'testmail@test.it',
            'to'   => $to,
            'subject' => $subject,
            'text' => $text,
            'html' => $html
        ));

    }


}