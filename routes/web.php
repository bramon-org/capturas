<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

$router->get('/', function () use ($router) {
    $client = new \App\Services\HttpClient(env('BRAMON_API_TOKEN', 'BRAMON-TOKEN'));

    $filter = [];
    $page = 1;
    $limit = 9;
    $filters = urldecode(http_build_query($filter));

    $estacoes = doRequest($client, 'GET', 'operator/stations?limit=1000');
    $capturas = doRequest($client, 'GET', "operator/captures?page={$page}&limit={$limit}&{$filters}");
    $radiantes = getRadiants();

    return view('home', [
        'version' => $router->app->version(),
        'estacoes' => $estacoes,
        'capturas' => $capturas,
        'radiantes' => $radiantes,
    ]);
});

function doRequest($client, string $method, string $uri, array $parameters = [])
{
    try {
        $response = $client->request($method, $uri, ['body' => json_encode($parameters)]);

        $json = $response->getBody()->getContents();
    } catch (ClientException|Error|GuzzleException $error) {
        $json = $error->getResponse()->getBody()->getContents();
    }

    $jsonConverted = mb_convert_encoding($json, "UTF-8");

    return json_decode($jsonConverted, true);
}

function getRadiants() {
    $radiants_collection = file( __DIR__  . '/../resources/data/radiants.txt', FILE_IGNORE_NEW_LINES);
    $radiants_final = [];

    foreach ($radiants_collection as $radiant) {
        $tmp = explode(':', $radiant);

        $radiants_final[ $tmp[0] ] = $tmp[1];
    }

    return $radiants_final;
}
