<?php

require('afm/bootstrap.inc.php');

use AFM\Application;
use AFM\Request;
use AFM\Response;

class HelloWorld
{
	public static function hello ($request, $response)
	{
		$response->write('Hello '.$request->param('name', 'you'));
	}
}

class TestClass
{
	public $app;
	
	function doCool ($request, $response)
	{
		if ($request->get('bar', false) !== false)
		{
			$this->app->abort(403);
			return;
		}
		$response->write('<h1>Provide a Query &quot;foo&quot;</h1>');
		$data = $request->get('foo', 'None');
		$response->write('Uppercase - '.strtoupper($data).' | Lowercase - '.strtolower($data).' | Normal - '.$data);
	}
}

$test = new TestClass();

$app = new Application();

$test->app = $app;

$app->route('/', function ($request, $response) {
	$response->write('Welcome to AppFrameMicro');
});
$app->route('/hello/{name}', ['HelloWorld', 'hello']);
$app->route('/cool', [$test, 'doCool']);

$app->notFound(function ($request, $response) {
	$response->status = 404;
	$response->write($request->URL.' not found');
});
$app->forbidden(function ($request, $response) {
	$response->status = 403;
	$response->write('Forbidden');
});

$app->run(new Request(), new Response());