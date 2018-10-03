<?php

namespace App\Model;

class Subject extends Base
{
    const TABLE = 'themes';

    protected $title;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public static function subjectAdd($title) {
        $sth = \App\App::getInstance()->getDb()->prepare(
            'INSERT INTO ' . static::TABLE . '(title)'
            . ' VALUES(:title)'
        );
        $sth->execute([':title' => $title]);
    }
   
    public static function countQuestionsAnswered($id) {
        $sth = \App\App::getInstance()->getDb()->prepare('SELECT COUNT(*) as cnt FROM questions WHERE subject_id = :id AND answer IS NOT NULL');
        $sth->execute([':id' => $id]);
        $result = $sth->fetch(\PDO::FETCH_OBJ)->cnt;
        
        return $result;
    }
    
    public static function countQuestionsUnAnswered($id) {
        $sth = \App\App::getInstance()->getDb()->prepare('SELECT COUNT(*) as cnt FROM questions WHERE subject_id = :id AND answer IS NULL');
        $sth->execute([':id' => $id]);
        $result = $sth->fetch(\PDO::FETCH_OBJ)->cnt;
        
        return $result;
    }
   
    public static function countQuestions($id) {
        $sth = \App\App::getInstance()->getDb()->prepare('SELECT COUNT(*) as cnt FROM questions WHERE subject_id = :id');
        $sth->execute([':id' => $id]);
        $result = $sth->fetch(\PDO::FETCH_OBJ)->cnt;
        
        return $result;
    }
    
    public static function getQuestions($id) {
        $sth = \App\App::getInstance()->getDb()->prepare('SELECT * FROM questions WHERE subject_id = :id');
        $sth->execute([':id' => $id]);
        $result = $sth->fetchAll(\PDO::FETCH_OBJ);
        
        return $result;
    }
    
    public static function getUnansweredQuestions($id) {
        $sth = \App\App::getInstance()->getDb()->prepare('SELECT * FROM questions WHERE answer IS NULL AND subject_id = :id ORDER BY created_at');
        $sth->execute([':id' => $id]);
        $result = $sth->fetchAll(\PDO::FETCH_OBJ);
        
        return $result;
    }
    
    public static function deleteQuestions($id) {
        $sth = \App\App::getInstance()->getDb()->prepare('DELETE FROM questions WHERE subject_id = :id');
        $sth->execute([':id' => $id]);
        
    }
    
    
   
    public static function fromRecord(\stdClass $record)
    {
        $item = new self();
        $item->setId($record->id);
        $item->setTitle($record->title);
        return $item;
    }
}