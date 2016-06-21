<?php

require('afm/bootstrap.inc.php');

use AFM\Application;
use AFM\Request;
use AFM\Response;

function sayHello($request, $response){
	$response->write('Hello '.$request->param('name', 'Mr. Nobody'));
}

$app = new Application();

$app->route('/', function ($request, $response) {
	$response->write('Welcome to AppFrameMicro');
});
$app->route('/hello/{name}', 'sayHello');

$app->notFound(function ($request, $response) {
	$response->status = 404;
	$response->write($request->URL.' not found');
});
$app->forbidden(function ($request, $response) {
	$response->status = 403;
	$response->write('Forbidden');
});

$app->run(new Request(), new Response());
