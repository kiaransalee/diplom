<?php

namespace App;

use App\Model\User;

class App
{
    static $app;

    /**
     * @var string Базовая директория проекта.
     */
    private $baseDir;

    /**
     * @var array Массив с настройками приложения.
     */
    private $config = [];

    private $db;
    private $twig;

    private $currentUser;

    private $messages;

    /**
     * @var Router
     */
    private $router;

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return \PDO
     */
    public function getDb()
    {
        if (!$this->db) {
            $connect_str = 'mysql:host=' . $this->config['db_host'] . ';dbname=' . $this->config['db_name'] . ';charset=utf8';
            $this->db = new \PDO ($connect_str, $this->config['db_user'], $this->config['db_pass'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        }

        return $this->db;
    }

    public function getTwig()
    {
        if (!$this->twig) {
            $loader = new \Twig_Loader_Filesystem($this->getBaseDir() . '/templates');
            $this->twig = new \Twig_Environment($loader);
        }

        return $this->twig;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if (!$this->router) {
            $router = new \App\Router();

            if (!empty($this->config['base_path'])) {
                $router->setBasePath($this->config['base_path']);
            }

            $this->router = $router;
        }

        return $this->router;
    }

    public function generateUrl($path)
    {
        return $this->router ? $this->router->generateUrl($path) : '';
    }

    public function getUser()
    {
        if (empty($this->currentUser)) {
            session_start();

            if (isset($_SESSION['user']) && ($_SESSION['user'] instanceof User)) {

                $this->currentUser = $_SESSION['user'];

            } else {
                $this->currentUser = new User();

                $this->currentUser->setId(0);
                $this->currentUser->setLogin('guest');
            }
        }

        return $this->currentUser;
    }

    public function login(User $user)
    {
        $this->getUser();

        $user = clone $user;
        $user->setPassword(null);

        $this->currentUser = $user;
        $_SESSION['user'] = $user;
    }

    public function logout()
    {
        $this->getUser();

        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
    }

    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    /**
     * Обрабатывает запрос пользователя.
     * @param string $currentUri Текущий относительный путь страницы (после слеша).
     * @return array
     */
    public function handleRequest($currentUri)
    {
        $template = 'pages/404.html.twig';
        $variables = [];
        $code = 404;

        $urlData = $this->getRouter()->findController($currentUri);

        if (!empty($urlData['controller']) && !empty($urlData['action'])) {
            if (class_exists($urlData['controller'])) {
                $controller = new $urlData['controller']($this);

                $params = [];
                if (!empty($urlData['params']) && is_array($urlData['params'])) {
                    $params = $urlData['params'];
                }

                $result = call_user_func_array([$controller, $urlData['action']], $params);

                if (!empty($result[0])) {
                    $template = $result[0];
                    if (isset($result[1]) && is_array($result[1])) {
                        $variables = $result[1];
                    }
                    $code = 200;
                }
            }
        }

        $twig = $this->getTwig();

        $twig->addFunction(new \Twig_Function('url', [$this, 'generateUrl']));

        $twig->addGlobal('current_uri', str_replace($this->getRouter()->getBasePath(), '', $currentUri));
        $twig->addGlobal('current_user', $this->getUser());
        $twig->addGlobal('messages', $this->messages);

        return [$code, $twig->render($template, $variables)];
    }

    public static function getInstance()
    {
        if (!self::$app) {
            self::$app = new self();
        }
        return self::$app;
    }
}