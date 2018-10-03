<?php

namespace App\Controller;

use App\Model\Question;
use App\Model\Subject;

class Home extends Base
{
    public function index()
    {
        $variables = [];
        $variables['page_title'] = 'Вопросы и ответы';

        $variables['questions'] = [];

        $questions = Question::findByStatus(Question::STATUS_ANSWERED);

        $subjectsIds = [];
        foreach ($questions as $question) {
            $subjectsIds[$question->getSubjectId()] = $question->getSubjectId();
            $variables['questions'][$question->getSubjectId()][] = $question;
        }

        $subjects = [];
        foreach (Subject::findAll() as $subject) {
            if (in_array($subject->getId(), $subjectsIds)) {
                $subjects[] = $subject;
            }
        }

        $variables['subjects'] = $subjects;

        return ['pages/home.html.twig', $variables];
    }
}