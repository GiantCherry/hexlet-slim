<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$companies = App\Generator::generate(100);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response, $args) {
    return $response->write('open something like (you can change id): /companies/5');
});

// BEGIN (write your solution here)
$app->get('/companies/{id}', function ($request, $response, $args) use ($companies) {
    $id = $args['id'];
    $company = collect($companies)->firstWhere('id', $id);

    if (empty($company)) {
        return $response->withStatus(404)->write('Page not found.');
    }

    return $response->write(json_encode($company));
});

$app->run();
// END
