<?php
/**
 * Created by IntelliJ IDEA.
 * User: rgoyard
 * Date: 16/11/14
 * Time: 11:47
 */
class Message
{

    private $id;
    private $content;
    private $date;
    private $users_id;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsersId()
    {
        return $this->users_id;
    }

    /**
     * @param mixed $users_id
     */
    public function setUsersId($users_id)
    {
        $this->users_id = $users_id;
    }

    /**
     * Retourne l'auteur du message
     * @return User
     */
    public function getAuthor(){
        $db = new Database();
        return $db->findUserById($this->users_id);
    }

} 