<?php
namespace App\Services;

use GuzzleHttp\Client;

final class HttpClient extends Client
{
    const USER_AGENT = 'BRAMON HTTP CLient';
    const PRODUCTION  = 'production';
    const SANDBOX = 'sandbox';

    const CLIENT_URLS = [
        self::PRODUCTION => 'https://api.bramonmeteor.org/v1/',
        self::SANDBOX => 'https://api.bramonmeteor.org/v1/',
    ];

    /**
     * @var string
     */
    public string $baseUrl = '/';

    /**
     * @var array
     */
    public array $headers;

    /**
     * Initialize the HTTP Client used by SDK.
     *
     * @param string|null $token The access token
     * @param string $mode The environment
     */
    public function __construct(string $token = null, string $mode = self::PRODUCTION)
    {
        $this->baseUrl = self::CLIENT_URLS[$mode] ?? self::PRODUCTION;
        $this->headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => self::USER_AGENT,
            'Cache-Control' => 'no-cache',
            'Connection' => 'Keep-Alive',
            'Accept' => 'application/json',
        ];

        if ($token) {
            $this->headers['Authorization'] = 'Bearer ' . $token;
        }

        parent::__construct([
            'base_uri' => $this->baseUrl,
            'headers' => $this->headers,
            'verify' => false,
        ]);
    }
}