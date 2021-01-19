<?php

namespace App\Http\Controllers;

use App\Services\HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected HttpClient $httpClient;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->httpClient = new HttpClient(env('BRAMON_API_TOKEN', 'BRAMON-TOKEN'));
    }

    /**
     * Made an HTTP request.
     *
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @return mixed
     */
    protected function doRequest(string $method, string $uri, array $parameters = [])
    {
        try {
            $response = $this->httpClient->request($method, $uri, ['body' => json_encode($parameters)]);

            $json = $response->getBody()->getContents();
        } catch (ClientException|Error|GuzzleException $error) {
            $json = $error->getResponse()->getBody()->getContents();
        }

        $jsonConverted = mb_convert_encoding($json, "UTF-8");

        return json_decode($jsonConverted, true);
    }
}
