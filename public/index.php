<?php

use function Stringy\create as s;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$users = App\Generator::generate(100);

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

$app->get('/users/new', function ($request, $response) {
    $params = [
        'user' => [],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});

$app->get('/users', function ($request, $response) use ($users) {
    $term = $request->getQueryParam('term');
    $searchAnswer = [];
    if (!empty($term)) {
        foreach ($users as $user) {
            if (strpos(mb_strtolower($user['nickname']), mb_strtolower($term)) !== false) {
                $searchAnswer [] = $user;
            }
        }
    }
    if (empty($searchAnswer)) {
        $searchAnswer = $users;
    }
    $params = [
        'term' => $term,
        //'users' => $users,
        'searchAnswer' => $searchAnswer
    ];
    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});

$app->post('/users', function ($request, $response) {
     function validate($user) {
        $errors = [];
        if (empty($user['name'])) {
            $errors['name'] = "Can't be blank";
        }

     return $errors;
    }

    $user = $request->getParsedBodyParam('user');
    $errors = validate($user);
    //$errors = $validator->validate($user);
//    if (count($errors) === 0) {
//        $repo->save($user);
//        return $response->withHeader('Location', '/')
//            ->withStatus(302);
//    }
    $params = [
        'user' => $user,
        'errors' => $errors
    ];

    //TODO $user -> json -> file
    $handle = fopen('users.txt', 'a+');
    fwrite($handle, json_encode($user));
    fclose($handle);

    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});
// END

$app->run();
