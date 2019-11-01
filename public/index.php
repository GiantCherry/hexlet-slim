<?php

use App\Validator;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$repo = new App\Repository();

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$router = $app->getRouteCollector()->getRouteParser();

$app->get('/', function ($request, $response) use ($router) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/courses', function ($request, $response) use ($repo, $router) {
    //$router->urlFor('/courses');

    $params = [
        'courses' => $repo->all(),
        'route' => $router
    ];
    return $this->get('renderer')->render($response, 'courses/index.phtml', $params);
});

// BEGIN (write your solution here)
$app->get('/courses/new', function ($request, $response) use ($repo, $router) {
    //$router->urlFor('/courses/new');

    $params = [
        'course' => [],
        'errors' => [],
        'route' => $router
    ];

    return $this->get('renderer')->render($response, 'courses/new.phtml', $params);
});

$app->post('/courses', function ($request, $response) use ($repo, $router) {
    $course = $request->getParsedBodyParam('course');

    $validator = new Validator();
    $errors = $validator->validate($course);

    if (count($errors) === 0) {
        $repo->save($course);
        return $response->withHeader('Location', '/courses')
            ->withStatus(302);
    }

    //$router->urlFor('courses');

    $params = [
        'course' => $course,
        'errors' => $errors,
        'route' => $router
    ];

    return $this->get('renderer')->render($response->withStatus(422), 'courses/new.phtml', $params);
});
// END

$app->run();
