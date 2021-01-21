<?php

namespace App\Http\Controllers;

use App\Services\HttpClient;
use Carbon\Carbon;
use Error;
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
            $json = json_encode([
                'error' => [
                    'message' => $error->getMessage(),
                    'debug' => env('APP_DEBUG', false) ? $error->getTraceAsString() : false,
                ],
                'data' => [],
                'current_page' => 1,
                'first_page_url' => '',
                'from' => '',
                'last_page' => 1,
                'last_page_url' => '',
                'next_page_url' => '',
                'path' => '',
                'per_page' => 1,
                'prev_page_url' => '',
                'to' => '',
                'total' => 1,
            ]);

            info([$error->getMessage(), $error->getTraceAsString()]);
        }

        $jsonConverted = mb_convert_encoding($json, "UTF-8");

        return json_decode($jsonConverted, true);
    }

    /**
     * @return mixed
     */
    protected function getAllStations()
    {
        if (Cache::has('stations')) {
            return Cache::get('stations');
        }

        $stations = $this->doRequest('GET', 'stations?limit=1000');

        Cache::put('stations', $stations, Carbon::now()->addMinutes(10));

        return $stations;
    }

    /**
     * @return array|mixed
     */
    protected function getRadiants()
    {
        if (Cache::has('radiants')) {
            return Cache::get('radiants');
        }

        $radiants = file( __DIR__  . '/../../../resources/data/radiants.txt', FILE_IGNORE_NEW_LINES);
        $radiantsIndexed = [];

        foreach ($radiants as $radiant) {
            $tmp = explode(':', $radiant);

            $radiantsIndexed[ $tmp[0] ] = $tmp[1];
        }

        Cache::put('radiants', $radiantsIndexed, Carbon::now()->addMinutes(10));

        return $radiantsIndexed;
    }
}
