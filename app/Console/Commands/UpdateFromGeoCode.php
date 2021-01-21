<?php

namespace App\Console\Commands;

use App\Services\HttpClient;
use Carbon\Carbon;
use Error;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateFromGeoCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:stations-by-geocode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stations by your geocode.';

    /**
     * @var HttpClient
     */
    protected HttpClient $httpClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->httpClient = new HttpClient(
            env('BRAMON_API_TOKEN', 'BRAMON-TOKEN'),
            env('BRAMON_API_ROLE', HttpClient::ROLE_PUBLIC),
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Recuperando estações...');
        $estacoes = $this->getAllStations();

        foreach ($estacoes['data'] as $estacao) {
            $result = $this->getLocation($estacao['latitude'], $estacao['longitude']);
            $id = $estacao['id'];

            if (!array_key_exists('address', $result)) {
                $this->info(print_r($estacao, true));
                $this->error(print_r($result, true));
                continue;
            }

            $this->info('....');

            try {
                $data = array_merge(
                    $estacao,
                    [
                        'city' => $result['address']['city'] ?? $result['address']['city_district'] ?? $result['address']['county'] ?? $result['address']['town'] ?? $result['address']['village'],
                        'state' => $result['address']['state'] ?? 'São Paulo',
                        'country' => strtoupper($result['address']['country_code'] ?? 'BR'),
                        'fov' => (int) $estacao['fov'] ?? 100,
                        'azimuth' => (int) $estacao['azimuth'] ?? 0,
                        'elevation' => (int) $estacao['elevation'] ?? 0,
                        'camera_lens' => $estacao['camera_lens'] ?? 'Generic',
                        'camera_model' => $estacao['camera_model'] ?? 'Generic',
                        'camera_capture' => $estacao['camera_capture'] ?? 'Generic',
                    ]
                );
            } catch (\ErrorException $exception) {
                $this->info($exception->getMessage());
            }

            $result = $this->doRequest('PUT', "stations/{$id}", $data);

            if (!empty($result)) {
                $this->info(print_r($result, true));
            }
        }

        $this->info('Done.');
    }

    protected function getAllStations()
    {
        $stations = $this->doRequest('GET', 'stations?limit=1000');

        return $stations;
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

    /**
     * @param $lat
     * @param $lng
     * @return mixed
     */
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
}
