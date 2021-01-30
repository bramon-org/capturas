<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;

final class HttpClient extends Client
{
    const USER_AGENT = 'BRAMON HTTP CLient';

    const PRODUCTION  = 'production';
    const SANDBOX = 'sandbox';

    const ROLE_ADMIN = 'admin';
    const ROLE_PUBLIC = 'public';
    const ROLE_OPERATOR = 'operator';

    const CLIENT_URLS = [
        self::PRODUCTION => 'https://api.bramonmeteor.org/v1/',
        self::SANDBOX => 'https://api-sandbox.bramonmeteor.org/v1/',
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
     * @param string $role
     * @param string $mode The environment
     */
    public function __construct(string $token = null, string $role = self::ROLE_PUBLIC, string $mode = self::PRODUCTION)
    {
        $this->baseUrl = self::CLIENT_URLS[$mode] ?? self::PRODUCTION;
        $this->headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => self::USER_AGENT,
            'Accept' => 'application/json',
            'Cache-Control' => 'no-cache',
            'Connection' => 'Keep-Alive',
        ];

        if ($token) {
            $this->headers['Authorization'] = 'Bearer ' . $token;
        }

        $stack = HandlerStack::create();
        $stack->push(new CacheMiddleware(), 'cache');

        parent::__construct([
            'base_uri' => "{$this->baseUrl}{$role}/",
            'headers' => $this->headers,
            'verify' => false,
            'handler' => $stack,
        ]);
    }
}
