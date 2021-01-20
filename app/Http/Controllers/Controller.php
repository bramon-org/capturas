<?php

namespace App\Http\Controllers;

use App\Services\HttpClient;
use Carbon\Carbon;
use Error;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
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
        $this->httpClient = new HttpClient(
            env('BRAMON_API_TOKEN', 'BRAMON-TOKEN'),
            env('BRAMON_API_ROLE', HttpClient::ROLE_SHARED),
        );
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
            $json = json_encode(['error' => $error->getMessage()]);

            info($error->getTraceAsString());
        }

        $jsonConverted = mb_convert_encoding($json, "UTF-8");

        return json_decode($jsonConverted, true);
    }

    protected function getLocation($lat, $lng)
    {
        try {
            $client = new Client([
                'base_uri' => "https://nominatim.openstreetmap.org/",
                'headers' => [
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'BRAMON Client',
                    'Cache-Control' => 'no-cache',
                    'Connection' => 'Keep-Alive',
                    'Accept' => 'application/json',
                ],
                'verify' => false,
            ]);
            $response = $client->request('GET', "reverse.php?lat={$lat}&lon={$lng}&zoom=18&format=jsonv2");

            $json = $response->getBody()->getContents();
        } catch (ClientException|Error|GuzzleException $error) {
            $json = $error->getResponse()->getBody()->getContents();
        }

        return json_decode($json, true);
    }

    protected function getAllStations()
    {
        if (Cache::has('stations')) {
            return json_decode(Cache::get('stations'), true);
        }

        $stations = $this->doRequest('GET', 'stations?limit=1000');

        Cache::put('stations', json_encode($stations), Carbon::now()->addMinutes(60));

        return $stations;
    }

    protected function getRadiants()
    {
        $radiants = file( __DIR__  . '/../../../resources/data/radiants.txt', FILE_IGNORE_NEW_LINES);
        $radiantsIndexed = [];

        foreach ($radiants as $radiant) {
            $tmp = explode(':', $radiant);

            $radiantsIndexed[ $tmp[0] ] = $tmp[1];
        }

        return $radiantsIndexed;
    }
}
