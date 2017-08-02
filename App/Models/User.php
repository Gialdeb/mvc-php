<?php

namespace App\Models;

use App\Mail;
use App\Token;
use PDO;
use \Core\Model;

/**
 * Example user model
 *
 * PHP version 7.0
 *
 */
class User extends Model
{

    public $errors = array();


    /**
     * Class Constructor
     *
     * @param array $data Array di valori Iniziali provenienti dal form
     *
     * @return void
     *
     * passo al $data un array di default perché se passo un oggeto PDO mi da errore
     */
    public function __construct($data = [])
    {
        // Converto il $data da array ad object con un ciclo foreach
        foreach ($data as $key => $value){
            $this->$key = $value;

        }
    }


    /**
     * Salvo l'utente con i valori che passa
     *
     * @return bool
     */

    public function save()
    {
        $this->validate();

        if(empty($this->errors)){

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            $stmt->execute();

            return true;

        }

        return false;

    }

    /**
     * Verifico che i dati che passano dal form siano compilati correttamente
     *
     * @return void
     */

    public function validate(){
        //Name
        if ($this->name == ''){
            $this->errors[] = 'Il nome è richiesto';
        }

        //email
        if (filter_var($this->email ,  FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Email non valida';
        }
        // se l'email esiste
        if(static::emailExists($this->email)){
            $this->errors[] = 'Email già presente nei nostri sistemi';
        }

        // password
        //        if ($this->password != $this->password_confirmation){
        //            $this->errors[] = 'Le password non coincidono';
        //        }

        if (strlen($this->password) < 6) {
            $this->errors[] = 'Si prega di inserire più di sei caratteri per la password';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'La password richiede almeno una lettera';
        }
        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'La password richiede almeno un numero';
        }

    }

    /**
     * Verifico se l'email già esiste nel database
     *
     * @return string $email
     *
     * @return boolean True se l'email già esiste, altrimenti falso
     *
     */

    public static function emailExists($email){

        return static::findByEmail($email) !== false;

    }

    /**
     * Cerco l'email nel database
     *
     * @param string $email
     *
     * @return mixed User oggetto se trovato, altrimenti falso
     *
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        // Di base PDO passa un array lo sostituisco con la classe
//        $stmt->setFetchMode(PDO::FETCH_CLASS, 'App\Models\User');
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Autenticazione utente da username e password
     *
     * @param string $email indirizzo email
     * @param string $password password
     *
     * @return mixed User oggetto se trovato, altrimenti l'autenticazione fallisce
     *
     */

    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);
        if($user){
            if(password_verify($password, $user->password_hash)){
                return $user;
            }
        }
        return false;
    }

    /**
     * Cerco l'utente per ID
     *
     * @param integer $id Utente
     *
     * @return mixed User oggetto se trovato
     *
     */

    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();


    }


    /**
     * Inserisco un nuovo unico token nella tabella remembered_login
     * per ogni nuovo utente
     *
     * @return boolean se Vero ritorna ricorda l'utente, altrimenti falso
     *
     */

    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30; // 30 giorni

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at) 
                VALUE (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();

    }

    /**
     * Invio il reset della password all'utente che non la ricordo
     *
     * @param string $email Indirizzo email
     * 
     * @return void
     *
     */

    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if($user){

            // Processo invio password

            if($user->startPasswordReset()){

                //Invio l'email
                $user->sendPasswordResetEmail();

            }

        }
    }

    /**
     * Processo di reset password che genera un nuovo token
     *
     * @return void
     *
     */
    public function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2; // 2 ore da adesso

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();

    }

    /**
     * Invio password con le istruzioni all'utente
     *
     * @return void
     *
     */

    public function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST']. 'password/reset/' . $this->password_reset_token;

        $text = "Per piacere clicca sulla URL per resettare la tua password: $url";
        $html = "Per piacere clicca <a href=\"$url\">qui</a> per resettare la tua password.";

        Mail::send($this->email, 'Reset della Password', $text, $html);

    }

}
