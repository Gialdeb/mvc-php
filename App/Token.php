<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 01/08/17
 * Time: 17:15
 */

namespace App;

    /**
     * Random Token
     *
     */

class Token
{
    /**
     * Valore del Token
     *
     * @var array
     */
    protected $token;

    /**
     * Classe Costruttore. Creo un nuovo random token
     * 
     * @return void
     */

    public function __construct()
    {
        $this->token = bin2hex(random_bytes(16)); // 16 bytes = 128 bits = 32 hex caratteri
    }

    /**
     * Mi ritorna il token
     *
     * @return string valore
     */

    public function getValue($token_value = null)
    {
        if($token_value){
            $this->token = $token_value;
        } else {
            $this->token = bin2hex(random_bytes(16)); // 16 bytes = 128 bits = 32 hex caratteti
        }

    }

    /**
     * Mi ritorna il valore hash del token
     *
     * @return string hash valore
     */

    public function getHash()
    {
        return hash_hmac('sha256', $this->token, Config::SECRET_KEY); // sha256 = 64 chars
    }
    
}