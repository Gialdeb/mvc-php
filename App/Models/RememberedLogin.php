<?php
/**
 * Creato da Giuseppe Alessandro De Blasio.
 * User: Giuseppe Alessandro De Blasio
 * Web: www.giuseppealessandrodeblasio.it
 * Email: info@giuseppealessandrodeblasio.it
 * Date: 01/08/2017
 * Time: 21.17
 */


/**
 * Remembered Login model
 */



namespace App\Models;

use PDO;
use App\Token;
use Core\Model;

class RememberedLogin extends Model
{
    /**
     * Cerca di ricordare il Login dal Token
     *
     * @param string $token Login Token
     *
     * @return mixed ritorna l'oggetto login se trovato, altrimenti falso
     */

    public static function findByToken($token)
    {
        $token = new Token($token);
        $token_hash = $token->getHash();

        $sql = 'SELECT * FROM remembered_logins WHERE token_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $token_hash, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Cerca User model associato con remembered Login
     *
     * @return User model
     *
     */

    public function getUser()
    {
        return User::findByID($this->user_id);
    }


    /**
     * Verifico se il token ricordami è scaduto o no basandomi sulla data corrente
     *
     * @return boolean True se il cookie è scaduto, altrimenti falso
     *
     */

    public function hasExpired()
    {
        return strtotime($this->expires_at) < time();
    }

    /**
     * Elimino questo Modello
     *
     * @return void
     *
     */


    public function delete()
    {
        $sql = 'DELETE FROM remembered_logins WHERE token_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $this->token_hash, PDO::PARAM_STR);

        $stmt->execute();
    }

}