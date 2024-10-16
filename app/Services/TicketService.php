<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketService
{
    protected $ticketServiceUrl;

    public function __construct()
    {
        $this->ticketServiceUrl = env('TICKET_SERVICE_URL') . "/api/v1";
    }

    public function get($endpoint)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get($this->ticketServiceUrl . $endpoint);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Failed to fetch data from TicketService', ['endpoint' => $endpoint, 'response' => $response->body()]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error while fetching data from TicketService', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function post($endpoint, array $data)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post($this->ticketServiceUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Failed to post data to TicketService', ['endpoint' => $endpoint, 'response' => $response->body()]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error while posting data to TicketService', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
