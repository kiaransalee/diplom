<?php

namespace App\Controller;

class User extends Base
{
    public function login()
    {
        $variables = [];
        $variables['page_title'] = 'Вход';

        $login = isset($_POST['login']) ? $_POST['login'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;

        $variables['login'] = $login;

        if ($login) {

            $user = \App\Model\User::findByLogin($login);

            if ($user && ($user->getPassword() == $password)) {

                $this->app->login($user);

                $this->redirect('/admin');
            } else {
                $this->app->addMessage('Неправильный логин или пароль.');
            }
        }

        return ['pages/login.html.twig', $variables];
    }

    public function logout()
    {
        $this->app->logout();
        $this->redirect('/');
    }
}