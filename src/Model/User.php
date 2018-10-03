<?php

namespace App\Model;

class User extends Base
{
    const TABLE = 'admins';

    protected $login;
    protected $password;

    public function isAuthorized()
    {
        return $this->getId() > 0;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public static function findByLogin($login)
    {
        $item = null;

        $sth = \App\App::getInstance()->getDb()->prepare('SELECT * FROM ' . static::TABLE . ' WHERE login = :login LIMIT 1');
        $sth->execute([':login' => $login]);

        while ($record = $sth->fetch(\PDO::FETCH_OBJ)) {
            $item = static::fromRecord($record);
        }

        return $item;
    }

    public function save()
    {
        $db = \App\App::getInstance()->getDb();
        $sth = $db->prepare(
            'INSERT INTO ' . static::TABLE . '(id, login, password)'
            . ' VALUES(:id, :login, :password)'
            . ' ON DUPLICATE KEY UPDATE login = :login, password = :password'
        );

        $success = $sth->execute([
            ':id' => $this->getId(),
            ':login' => $this->getLogin(),
            ':password' => $this->getPassword(),
        ]);

        if ($success) {

            if (!$this->getId()) {
                $this->setId($db->lastInsertId());
            }

            return true;
        }

        return false;
    }

    static function fromRecord(\stdClass $record)
    {
        $item = new static();

        $item->setId($record->id);
        $item->setLogin($record->login);
        $item->setPassword($record->password);

        return $item;
    }
    
   
}
    
     