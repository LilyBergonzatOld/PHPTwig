<?php

/**
 * Created by IntelliJ IDEA.
 * User: remi
 * Date: 23/10/2014
 * Time: 18:07
 */
class Database
{

    private $server = 'localhost';
    private $user = 'mycroblog_user';
    private $password = 'mycroblog_password';
    private $dbName = 'mycroblog';

    private $db;

    public function __construct()
    {
        $this->db = new mysqli($this->server, $this->user, $this->password, $this->dbName);
    }


    /**
     * Enregistre les données utilisateur en base.
     *
     * @param $login Le login
     * @param $password Le mot de passe
     * @param $email l'email de l'utilisateur
     *
     * @return bool
     */
    public function saveUser($login, $password, $email)
    {

        $sql = "INSERT INTO users (login, password, email) VALUES (?,?,?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $login, md5($password), $email);
        $stmt->execute();
        if ($stmt->affected_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Recupère l'utilisateur  en fonction du login et du password.
     *
     * @param $login Le login recherché
     * @param $password et son mot de passe
     * @return User
     * @throws Exception
     */
    public function getUserFrom($login, $password)
    {
        $sql = "SELECT id, login, email FROM users where login = ? AND password = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $login, md5($password));
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id_res, $login_res, $email_res);
            $stmt->fetch();
            $user = new User();
            $user->setId($id_res);
            $user->setLogin($login_res);
            $user->setEmail($email_res);
            return $user;
        } else {
            throw new Exception("Utilisateur Inconnu");
        }
    }

    public function addMessage($message)
    {
        if (isset($_SESSION['user']) && (!empty($_SESSION['user']))) {

            $sql = "INSERT INTO messages (content, date, users_id ) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssd', $message, date('Y-m-d H:i:s'), $_SESSION['user']->getId());
            $stmt->execute();
            if ($stmt->affected_rows == 1) {
                return true;
            } else {
                throw new Exception("Erreur lors de la sauvegarde du message");
            }
        } else {
            throw new Exception("Erreur accés interdit");
        }
    }

    public function getMessagesForCurrentUser()
    {

        if (isset($_SESSION['user']) && (!empty($_SESSION['user']))) {

            $sql = "SELECT id, content, 'date', users_id FROM messages WHERE users_id = ? ORDER BY 'date' ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('d', $_SESSION['user']->getId());
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows >= 0) {
                $stmt->bind_result($id_res, $content, $date, $user_id);
                $res = array();
                while ($stmt->fetch()) {
                    $message = new Message();
                    $message->setId($id_res);
                    $message->setContent($content);
                    $message->setDate($date);
                    $message->setUsersId($user_id);
                    array_push($res, $message);
                }

                return $res;
            } else {
                throw new Exception("Erreur lors de la récupération des messages");
            }
        } else {
            throw new Exception("Erreur accés interdit");
        }
    }

    /**
     * Recherche un utilisateur par son ID
     *
     * @param $users_id
     * @throws Exception
     * @return User
     */
    public function findUserById($users_id)
    {
        $sql = "SELECT id, login, email FROM users where id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('d',$users_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id_res, $login_res, $email_res);
            $stmt->fetch();
            $user = new User();
            $user->setId($id_res);
            $user->setLogin($login_res);
            $user->setEmail($email_res);
            return $user;
        } else {
            throw new Exception("Utilisateur Inconnu");
        }


    }
} 