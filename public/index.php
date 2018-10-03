<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

$baseDir = __DIR__ . '/..';

require $baseDir . '/vendor/autoload.php';

$app = \App\App::getInstance();
$app->setBaseDir($baseDir);

$app->setConfig(require "$baseDir/config.php");

$router = $app->getRouter();
$router->addGet('/', '\App\Controller\Home@index');
$router->addGet('/login', '\App\Controller\User@login');
$router->addPost('/login', '\App\Controller\User@login');
$router->addGet('/logout', '\App\Controller\User@logout');

$router->addGet('/ask', '\App\Controller\Question@ask');
$router->addPost('/ask', '\App\Controller\Question@ask');

$router->addGet('/admin', '\App\Controller\Admin@index');

$router->addGet('/admin/users', '\App\Controller\Admin@users');
$router->addGet('/admin/users/add', '\App\Controller\Admin@userAdd');
$router->addPost('/admin/users/add', '\App\Controller\Admin@userAdd');
$router->addGet('/admin/users/(\d+)', '\App\Controller\Admin@userView', ['id' => 1]);
$router->addGet('/admin/users/(\d+)/edit', '\App\Controller\Admin@userEdit', ['id' => 1]);
$router->addPost('/admin/users/(\d+)/edit', '\App\Controller\Admin@userEdit', ['id' => 1]);
$router->addGet('/admin/users/(\d+)/delete', '\App\Controller\Admin@userDelete', ['id' => 1]);
$router->addPost('/admin/users/(\d+)/delete', '\App\Controller\Admin@userDelete', ['id' => 1]);

$router->addGet('/admin/subjects', '\App\Controller\Admin@subjects');
$router->addGet('/admin/subjects/add', '\App\Controller\Admin@subjectAdd');
$router->addPost('/admin/subjects/add', '\App\Controller\Admin@subjectAdd');
$router->addGet('/admin/subjects/(\d+)', '\App\Controller\Admin@subjectView', ['id' => 1]);
$router->addGet('/admin/subjects/(\d+)/edit', '\App\Controller\Admin@subjectEdit', ['id' => 1]);
$router->addGet('/admin/subjects/(\d+)/delete', '\App\Controller\Admin@subjectDelete', ['id' => 1]);

$router->addGet('/admin/questions', '\App\Controller\Admin@questions');
$router->addGet('/admin/questions/add', '\App\Controller\Admin@questionAdd');
$router->addGet('/admin/questions/(\d+)', '\App\Controller\Admin@questionView', ['id' => 1]);
$router->addGet('/admin/questions/(\d+)/edit', '\App\Controller\Admin@questionEdit', ['id' => 1]);
$router->addPost('/admin/questions/(\d+)/edit', '\App\Controller\Admin@questionEdit', ['id' => 1]);
$router->addGet('/admin/questions/(\d+)/delete', '\App\Controller\Admin@questionDelete', ['id' => 1]);
$router->addGet('/admin/questions/(\d+)/hide', '\App\Controller\Admin@questionHide', ['id' => 1]);
$router->addGet('/admin/questions/(\d+)/public', '\App\Controller\Admin@questionPublic', ['id' => 1]);
$router->addGet('/admin/questions/(\d+)/answer', '\App\Controller\Admin@questionAnswer', ['id' => 1]);
$router->addPost('/admin/questions/(\d+)/answer', '\App\Controller\Admin@questionAnswer', ['id' => 1]);
$router->addGet('/admin/questions/(\d+)/unanswered', '\App\Controller\Admin@questionUnanswered', ['id' => 1]);

list($responseCode, $content) = $app->handleRequest($_SERVER['REQUEST_URI']);

http_response_code($responseCode);

print $content;