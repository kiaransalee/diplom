<?php

namespace App\Controller;

use App\App;

abstract class Base
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function redirect($path)
    {
        header('Location: ' . $this->app->generateUrl($path), true, 301);
        exit;
    }
}