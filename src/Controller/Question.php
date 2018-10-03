<?php

namespace App\Controller;

class Question extends Base
{
    public function ask()
    {
        $variables = [];
        $variables['page_title'] = 'Задать вопрос';

        $subjects = \App\Model\Subject::findAll();
        $variables['subjects'] = $subjects;

        if (!empty($_POST)) {
            $question = new \App\Model\Question();

            $question->setStatus(\App\Model\Question::STATUS_UNANSWERED);
            $question->setSubjectId($_POST['subject']);
            $question->setUser($_POST['user']);
            $question->setEmail($_POST['email']);
            $question->setText($_POST['text']);

            if ($question->save()) {
                $this->app->addMessage('Вопрос принят');
            } else {
                $this->app->addMessage('Ошибка');
            }
        }

        return ['pages/ask.html.twig', $variables];
    }


}