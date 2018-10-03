<?php

namespace App;

class Router
{
    protected $basePath = '';
    protected $urls = [];

    /**
     * Добавление роутеров
     * @param $url урл
     * @param $controllerAndAction пример: BookController@getUpdate
     */
    public function addGet($url, $controllerAndAction, $params = [])
    {
        $this->add('GET', $url, $controllerAndAction, $params);
    }
    /**
     * Добавление роутеров
     * @param $url урл
     * @param $controllerAndAction пример: BookController@postUpdate
     */
    public function addPost($url, $controllerAndAction, $params = [])
    {
        $this->add('POST', $url, $controllerAndAction, $params);
    }
    /**
     * Добавление роутеров
     * @param $url урл
     * @param $controllerAndAction пример: BookController@list
     */
    public function add($method, $url, $controllerAndAction, $params)
    {
        list($controller, $action) = explode('@', $controllerAndAction);
        $this->urls[$method][$url] = [
            'controller' => $controller,
            'action' => $action,
            'params' => $params
        ];
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    public function generateUrl($route)
    {
        return $this->basePath . $route;
    }

    /**
     * Подключение контроллеров
     * @param $url текущий урл
     */
    public function findController($currentUri)
    {
        if (strpos($currentUri, $this->basePath) !== 0) {
            // Если текущий путь не содержит базовый путь, то считаетаем, что надо открыть главную.
            $currentUri = '/';
        } else {
            // '/base/login' => '/login'
            $currentUri = str_replace($this->basePath, '', $currentUri);
        }

        $method = $_SERVER['REQUEST_METHOD']; // GET / POST

        if (isset($this->urls[$method])) {
            foreach ($this->urls[$method] as $url => $urlData) {
                if (preg_match('#^' . $url . '$#', $currentUri, $matches)) {

                    $params = [];
                    foreach ($urlData['params'] as $param => $i) {
                        $params[$param] = $matches[$i];
                    }

                    $urlData['params'] = $params;

                    return $urlData;
                }
            }
        }
    }
}