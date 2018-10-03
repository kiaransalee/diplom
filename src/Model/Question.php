<?php

namespace App\Model;

class Question extends Base
{
    const TABLE = 'questions';

    const STATUS_UNANSWERED = 1;
    const STATUS_ANSWERED = 2;
    const STATUS_HIDDEN = 3;

    protected $createdAt;

    protected $status;

    protected $subjectId;

    protected $text;

    protected $answer;

    protected $user;

    protected $email;

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
         $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * @param mixed $subjectId
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId = $subjectId;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
        
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public static function getUnansweredQuestions() {
        $sth = \App\App::getInstance()->getDb()->prepare('SELECT * FROM questions WHERE answer IS NULL ORDER BY created_at');
        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_OBJ);
        
        return $result;
    }
    
    public function save()
    {
        $db = \App\App::getInstance()->getDb();

        if (!$this->createdAt) {
            $this->createdAt = date('Y-m-d H:i:s');
        }

        $sth = $db->prepare(
            'INSERT INTO questions(id, text, answer, subject_id, status, user, email, created_at)'
            . ' VALUES(:id, :text, :answer, :subject_id, :status, :user, :email, :created_at)'
            . ' ON DUPLICATE KEY UPDATE text = :text, answer = :answer, subject_id = :subject_id, 
            status = :status, user = :user, email = :email, created_at = :created_at'
        );

        $success = $sth->execute([
            ':id' => $this->getId(),
            ':status' => $this->getStatus(),
            ':text' => $this->getText(),
            ':answer' => $this->getAnswer(),
            ':subject_id' => $this->getSubjectId(),
            ':user' => $this->getUser(),
            ':email' => $this->getEmail(),
            ':created_at' => $this->getCreatedAt(),
        ]);

        if ($success) {

            if (!$this->getId()) {
                $this->setId($db->lastInsertId());
            }

            return true;
        }

        return false;
    }

    /**
     * @param $status
     * @return static[]
     */
    public static function findByStatus($status)
    {
        $items = [];

        $sth = \App\App::getInstance()->getDb()->prepare('SELECT * FROM ' . static::TABLE . ' WHERE status = :status');
        $sth->execute([':status' => $status]);

        while ($record = $sth->fetch(\PDO::FETCH_OBJ)) {
            $items[] = static::fromRecord($record);
        }

        return $items;
    }

    public static function fromRecord(\stdClass $record)
    {
        $item = new static();

        $item->setId($record->id);
        $item->setSubjectId($record->subject_id);
        $item->setCreatedAt($record->created_at);
        $item->setText($record->text);
        $item->setStatus($record->status);
        $item->setAnswer($record->answer);
        $item->setUser($record->user);
        $item->setEmail($record->email);

        return $item;
    }
}