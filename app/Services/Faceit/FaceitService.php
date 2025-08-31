<?php
namespace  App\Services\Faceit;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class FaceitService
{

    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('faceit.base_url');
        $this->apiKey = config('faceit.api_key');
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function get(string $endpoint, array $query = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->get("{$this->baseUrl}/{$endpoint}", $query);

        if (!$response->successful()) {
            throw new \Exception("Faceit API error: " . $response->body());
        }

        return $response->json();
    }

}
