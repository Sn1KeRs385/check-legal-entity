<?php

namespace App\Modules\Checko;

use App\Modules\Checko\Dto\PhysicalPerson\GetPhysicalPersonDataDto;
use App\Modules\Checko\Dto\PhysicalPerson\PhysicalPersonDto;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class CheckoApiClient
{
    private GuzzleClient $apiClient;

    public function __construct()
    {
        /** @var string $apiUrl */
        $apiUrl = config('checko.api_url');

        $this->apiClient = new GuzzleClient([
            'base_uri' => $apiUrl,
            'timeout' => 10.0,
        ]);
    }

    public function getPhysicalPerson(int $inn): GetPhysicalPersonDataDto
    {
        $response = $this->callWithAuthorization('get', 'v2/person', [
            'query' => [
                'inn' => $inn
            ],
        ]);

        return GetPhysicalPersonDataDto::from(json_decode($response->getBody()->getContents(), true));
    }

    /**
     * @param array<string, mixed> $options
     */
    private function callWithAuthorization(string $method, string $url, array $options = []): ResponseInterface
    {
        /** @var string $apiKey */
        $apiKey = config('checko.api_key');

        $options['query'] = [
            'key' => $apiKey,
            ...($options['query'] ?? [])
        ];

        return $this->apiClient->request($method, $url, $options);
    }
}
