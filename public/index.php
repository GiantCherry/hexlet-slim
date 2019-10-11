<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$companies = App\Generator::generate(100);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->write('go to the /companies');
});

// BEGIN (write your solution here)
$app->get('/companies', function ($request, $response) use ($companies) {
    $page = $request->getQueryParam('page', 0);
    $per = $request->getQueryParam('per', 5);

    if ($page > 0) {
        $page--;
    }

    $getCompanies = function ($page = 0, $per = 5) use ($companies) {
        $notIdCompanies = array_map(function ($company) {
            return ['name' => $company['name'], 'phone' => $company['phone']];
        }, $companies);

        return array_slice($notIdCompanies, $page, $per);
    };

    return $response->write(json_encode($getCompanies($page * $per,  $per)));
});
// END

$app->run();
