<?php

namespace App\Controller;

use App\App;

class Admin extends Base
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        if (!$app->getUser()->isAuthorized()) {
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $variables = [];
        $variables['page_title'] = 'Админка';

        $sth = $this->app->getDb()->prepare("SHOW TABLES");
        $sth->execute();

        $tables = $sth->fetchAll(\PDO::FETCH_COLUMN);

        $variables['tables'] = $tables;

        return ['pages/admin.html.twig', $variables];
    }

    public function users()
    {
        $variables = [];
        $variables['page_title'] = 'Администраторы';

        $variables['users'] = \App\Model\User::findAll();

        return ['pages/admin_users.html.twig', $variables];
    }
    
    public function userAdd()
    {
        $variables = [];

        $user = new \App\Model\User();

        $variables['user'] = $user;

        if (!empty($_POST)) {

            $login = $_POST['login'] ?? null;
            $password = $_POST['password'] ?? null;

            $user->setLogin($login);
            $user->setPassword($password);

            if ($user->save()) {
                $this->redirect("/admin/users/{$user->getId()}");
            }
        }

        return ['pages/admin_user_add.html.twig', $variables];
    }

    public function userView($id)
    {
        $variables = [];

        $user = \App\Model\User::findById($id);

        $variables['user'] = $user;

        return ['pages/admin_user_view.html.twig', $variables];
    }

    public function userEdit($id)
    {
        $variables = ['page_title' => 'Изменить администратора'];

        $user = \App\Model\User::findById($id);

        $variables['user'] = $user;

        if (!empty($_POST)) {
            $user->setLogin($_POST['login']);

            if (!empty($_POST['password'])) {
                $user->setPassword($_POST['password']);
            }

            if ($user->save()) {
                $this->redirect("/admin/users/{$user->getId()}");
            }
        }

        return ['pages/admin_user_edit.html.twig', $variables];
    }

    /**
     * Страница удаления администратора.
     *
     * @param $id
     *
     * @return array
     */
    public function userDelete($id)
    {
        $variables = ['page_title' => 'Удалить администратора'];

        $user = \App\Model\User::findById($id);

        if (!$user) {
            $this->redirect('/admin/users');
        }

        $variables['user'] = $user;

        if (isset($_POST['delete'])) {
            if (\App\Model\User::delete($id)) {
                $this->redirect('/admin/users');
            }
        }
        
        return ['pages/admin_user_delete.html.twig', $variables];
    }

    public function subjects()
    {
        $variables = [];
        $variables['page_title'] = 'Темы';

        $subjects = \App\Model\Subject::findAll();
        $variables['subjects'] = $subjects;
        foreach ($subjects as $subject) {
        $id = $subject->getId();
        $variables['answ_count'][$id] = \App\Model\Subject::countQuestionsAnswered($id);
        $variables['unansw_count'][$id] = \App\Model\Subject::countQuestionsUnAnswered($id);
        $variables['quest_count'][$id] = \App\Model\Subject::countQuestions($id);
        }
        return ['pages/admin_subjects.html.twig', $variables];
    }

    public function subjectAdd()
    {
        $variables['page_title'] = 'Добавить новую тему';
        if (!empty($_POST['title'])){
        \App\Model\Subject::subjectAdd($_POST['title']);
        }
        return ['pages/admin_subject_add.html.twig', $variables];
    }

    public function subjectView($id)
    {
        $subject = \App\Model\Subject::findById($id);
        $variables['page_title'] = 'Просмотр темы';
        
        $variables['subject'] = $subject;

        $variables['questions'] = \App\Model\Subject::getQuestions($id);
    

        return ['pages/admin_subject_view.html.twig', $variables];
    }

    public function subjectEdit($id)
    {
        $variables = [];

        return ['', $variables];
    }

    public function subjectDelete($id)
    {
        $variables = ['page_title' => 'Удалить тему и все вопросы в данной теме'];
        $subject = \App\Model\Subject::findById($id);
        $variables['subject'] = $subject;
        \App\Model\Subject::delete($id);
        \App\Model\Subject::deleteQuestions($id);
        
        return ['pages/admin_subject_delete.html.twig', $variables];
    }

    public function questions()
    {
        $variables = [];
        $variables['page_title'] = 'Вопросы';

        $variables['questions'] = \App\Model\Question::findAll();

        return ['pages/admin_questions.html.twig', $variables];
    }

    public function questionAdd()
    {
        $variables = [];

        return ['', $variables];
    }

    public function questionView($id)
    {
        $variables = [];
        $variables['id'] = $id;

        $question = \App\Model\Question::findById($id);

        if ($question) {
            $variables['page_title'] = 'Вопрос №' . $question->getId();
            $variables['question'] = $question;
        }

        return ['pages/admin_question_view.html.twig', $variables];
    }

    public function questionEdit($id)
    {
        $variables = ['page_title' => 'Редактировать вопрос'];

        $question = \App\Model\Question::findById($id);
        $subjects = \App\Model\Subject::findAll();

        $variables['question'] = $question;
        $variables['subjects'] = $subjects;

         if (!empty($_POST)) {
        if (!empty($_POST['user'])) {
            $question->setUser($_POST['user']);
        }
            if (!empty($_POST['text'])) {
                $question->setText($_POST['text']);
            }
            
            if (!empty($_POST['answer'])) {
                $question->setAnswer($_POST['answer']);
                if(!empty($_POST['public'])){
                $question->setStatus(2);
            }
            }
            if(!empty($_POST['hide'])){
                $question->setStatus(3);
            }
            
            if(!empty($_POST['subject'])){
                $question->setSubjectId($_POST['subject']);
            }
            
            $question->save();

          }
          return ['pages/admin_question_edit.html.twig', $variables];
    }
    
    public function questionAnswer($id)
    {
        $variables = ['page_title' => 'Добавить ответ'];

        $question = \App\Model\Question::findById($id);

        $variables['question'] = $question;

         if (!empty($_POST)) {
            if (!empty($_POST['answer'])) {
                $question->setAnswer($_POST['answer']);
                if(isset($_POST['public'])){
                $question->setStatus(2);
            }
            if(isset($_POST['hide'])){
                $question->setStatus(3);
                }
            }
            
            $question->save();

          }
          return ['pages/admin_question_answer.html.twig', $variables];
    }
    
    public function questionDelete($id)
    {
         $variables = ['page_title' => 'Удалить вопрос'];
         $question = \App\Model\Question::findById($id);
         $variables['question'] = $question;
         \App\Model\Question::delete($id);
        
        return ['pages/admin_question_delete.html.twig', $variables];
        
    }
    
    public function questionHide($id)
    {
        $variables['page_title'] = 'Скрыть вопрос №' . $id;
        $question = \App\Model\Question::findById($id);
        $variables['question'] = $question;
        
        $question->setStatus(3);
        $question->save();
        
        return ['pages/admin_question_hide.html.twig', $variables];
    }
    
     public function questionPublic($id)
    {
        $variables['page_title'] = 'Опубликовать вопрос №' . $id;
        $question = \App\Model\Question::findById($id);
        $variables['question'] = $question;
        
        $question->setStatus(2);
        $question->save();
        
        return ['pages/admin_question_public.html.twig', $variables];
    }
    
    public function questionUnanswered()
    {
        $questions = \App\Model\Question::getUnansweredQuestions();
        $variables['questions_unansw'] = $questions;
        
        return ['pages/admin_questions.html.twig', $variables];
    }
    
}