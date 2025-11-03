<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BigDataApiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.bigdata.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.bigdata.api_key', env('BIG_DATA_API_KEY'));
    }

    /**
     * Search for companies by ticker symbol
     *
     * @param string $ticker
     * @return array|null
     */
    public function searchCompany(string $ticker): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('BIG_DATA_API_KEY is not configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-KEY' => $this->apiKey,
            ])->post("{$this->baseUrl}/knowledge-graph/companies", [
                'query' => strtoupper(trim($ticker)),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('BigData API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('BigData API exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}

