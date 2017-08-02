<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 01/08/17
 * Time: 09:56
 */

namespace App;

/**
 * Flash messaggio: messaggio memorizzato nella sessione
 */

class Flash
{

    /**
     * Messaggio di Successo
     * @var String
     */
    const SUCCESS = 'success';

    /**
     * Messaggio di Informazione
     * @var String
     */
    const INFO = 'info';

    /**
     * Messaggio di Warning
     * @var String
     */
    const WARNING = 'warning';

    /**
     * Messaggio di Warning
     * @var String
     */
    const ERROR = 'error';


    /**
     * Aggiungo Messaggio
     *
     * @param string $message Contenuto del messaggio
     * @param string $type Tipo di messaggio
     *
     * @return void
     */

    public static function addMessage($message, $type = 'success')
    {
        // Creo array nella sesione se non esiste
        if (! isset($_SESSION['flash_notifications'])){
            $_SESSION['flash_notifications'] = [];
        }

        // Inserisco il messaggio nell'array
        // $_SESSION['flash_notifications'][]= $message;
        $_SESSION['flash_notifications'][] =[
          'body' => $message,
          'type' => $type
        ];

    }
    
    /**
     * Mostro tutti i messagi
     * 
     * @return mixed Array di messaggi se settato altrimenti null
     */

    public static function getMessage()
    {
        if(isset($_SESSION['flash_notifications'])){
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $messages;

        }
    }
}