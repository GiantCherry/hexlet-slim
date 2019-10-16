<?php

use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

// Список пользователей
// Каждый пользователь – ассоциативный массив следующей структуры: id, firstName, lastName, email
$users = App\Generator::generate(100);
$myUsers = ['mike', 'mishel', 'adel', 'keks', 'kamila'];

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

// BEGIN (write your solution here)
$app->get('/users', function ($request, $response) use ($users) {
    $params = [
        'users' => $users
    ];

    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});

$app->get('/my-users', function ($request, $response) use ($myUsers) {
    $term = $request->getQueryParam('term');
    $searchAnswer = [];
    foreach ($myUsers as $user) {
        if (!empty($term)) {
            if (strpos(mb_strtolower($user), mb_strtolower($term)) !== false) {
                $searchAnswer [] = $user;
            }
        }
    }
    if (empty($searchAnswer)) {
        $searchAnswer = $myUsers;
    }

    $params = [
        'term' => $term,
        //'users' => $users,
        'searchAnswer' => $searchAnswer
    ];

    return $this->get('renderer')->render($response, 'users/my_users.phtml', $params);
});

$app->get('/users/{id}', function ($request, $response, $args) use ($users) {
    $id = $args['id'];
    if ($id > 0) {
        $id--;
    }

    $user = $users[$id];
    $params = ['user' => $user];

    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
});

$app->run();
// END
