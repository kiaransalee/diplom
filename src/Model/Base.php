<?php

namespace App\Model;

abstract class Base
{
    protected $id;

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
     * @param $id
     * @return static
     */
    public static function findById($id)
    {
        $item = null;

        $sth = \App\App::getInstance()->getDb()->prepare('SELECT * FROM ' . static::TABLE . ' WHERE id = :id');
        $sth->execute([':id' => $id]);

        while ($record = $sth->fetch(\PDO::FETCH_OBJ)) {
            $item = static::fromRecord($record);
        }

        return $item;
    }

    public static function findAll()
    {
        $items = [];

        $sth = \App\App::getInstance()->getDb()->prepare('SELECT * FROM ' . static::TABLE);
        $sth->execute();

        while ($record = $sth->fetch(\PDO::FETCH_OBJ)) {
            $items[] = static::fromRecord($record);
        }

        return $items;
    }

    public static function delete($id)
    {
        $sth = \App\App::getInstance()->getDb()->prepare('DELETE FROM ' . static::TABLE . ' WHERE id = :id');
        return $sth->execute([':id' => $id]);
    }

    abstract static function fromRecord(\stdClass $record);
}