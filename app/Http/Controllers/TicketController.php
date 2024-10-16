<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use App\Models\VendorTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\TicketService;

// class TicketController extends Controller
// {
//     public function index()
//     {

//         $matchdayResponse = Http::withHeaders([
//             'Accept' => 'application/json'
//         ])->get(env('TICKET_SERVICE_URL') . "/matchdays");
//         $matchdays = $matchdayResponse->json();

//         return view('tickets.index', [
//             'matchdays' => $matchdays,
//         ]);
//     }

//     public function show($id)
//     {
//         $matchday_response = Http::withHeaders([
//             'Accept' => 'application/json'
//         ])->get(env('TICKET_SERVICE_URL') . "/matchdays/{$id}");
//         $matchdayData = $matchday_response->json();

//         $recent_response = Http::withHeaders([
//             'Accept' => 'application/json'
//         ])->get(env('TICKET_SERVICE_URL') . '/matchdays/recent-matchdays');
//         $recent_stats = collect($recent_response->json());
    
//         return view('tickets.details', compact('recent_stats', 'matchdayData'));
//     }

//     public function store(Request $request)
//     {
//         $validatedData = $request->validate([
//             'home_team' => 'required|string|max:255',
//             'away_team' => 'required|string|max:255',
//             'match_time' => 'required|date',
//             'number_of_tickets' => 'required|integer|min:1',
//             'price' => 'required|numeric|min:0',
//         ]);

//         try {
//             $ticketServiceUrl = env('TICKET_SERVICE_URL') . '/matchdays';

//             $requestData = [
//                 'home_team' => $validatedData['home_team'],
//                 'away_team' => $validatedData['away_team'],
//                 'match_time' => $validatedData['match_time'],
//                 'number_of_tickets' => $validatedData['number_of_tickets'],
//                 'price' => $validatedData['price'],
//             ];

//             $response = Http::withHeaders([
//                                 'Accept' => 'application/json',
//                             ])
//                             ->post($ticketServiceUrl, $requestData);

//             if ($response->successful()) {
//                 return redirect()->route('tickets.index')->with('success', 'Matchday and Tickets created successfully!');
//             } else {
//                 return redirect()->back()->withErrors('Failed to create matchday: ' . $response->body());
//             }

//         } catch (\Exception $e) {
//             Log::error('Matchday creation failed', ['error' => $e->getMessage()]);

//             return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
//         }
//     }

//     public function showMuseumTickets()
//     {
//         $response = Http::withHeaders([
//             'Accept' => 'application/json'
//         ])->get(env('TICKET_SERVICE_URL') . "/tickets/get-museum-tickets");

//         $museum_tickets = $response->json()['available_tickets'];
    
//         return view('orders.museum-tickets', compact('museum_tickets'));
//     }

//     public function showSeasonDetails()
//     {
//         $response = Http::withHeaders([
//             'Accept' => 'application/json'
//         ])->get(env('TICKET_SERVICE_URL') . "/get-season-details");

//         $season_response = Http::withHeaders([
//             'Accept' => 'application/json'
//         ])->get(env('TICKET_SERVICE_URL') . "/get-season-tickets");

//         $seasonDetails = $response->json();
//         $seasonTickets = $season_response->json()['season_tickets'];
    
//         return view('tickets.season-ticket', compact(['seasonDetails', 'seasonTickets']));
//     }

//     public function updateSeasonDetails(Request $request)
//     {
//         $validatedData = $request->validate([
//             'seasonYear' => 'required|string|max:255',            
//             'price' => 'required|numeric|min:0',
//             'endDate' => 'required|date',
//         ]);
    
//         try {
//             $ticketServiceUrl = env('TICKET_SERVICE_URL') . '/update-season-details';

//             $requestData = [
//                 'season_year' => $validatedData['seasonYear'],
//                 'price' => $validatedData['price'],
//                 'end_date' => $validatedData['endDate'],
//             ];
//             $response = Http::retry(3, 200)
//                             ->timeout(10)
//                             ->withHeaders([
//                                 'Accept' => 'application/json',
//                             ])
//                             ->post($ticketServiceUrl, $requestData);
//             if ($response->successful()) {
//                 return redirect()->route('season.ticket')->with('success', 'Season updated successfully!');
//             } else {
//                 return redirect()->back()
//                                  ->withErrors('Failed to update season: ' . $response->body())
//                                  ->withInput();
//             }
    
//         } catch (\Exception $e) {
//             Log::error('Season Edit failed', ['error' => $e->getMessage()]);

//             return redirect()->back()
//                              ->with('error', 'An error occurred while updating the season. Please try again.')
//                              ->withInput();
//         }
//     }   

//     public function verifyBulkTicketPayment(Request $request)
//     {
//         $reference = $request->input('reference');
//         $cacheKey = 'paystack-verification-' . $reference;

//         // Check if the payment verification is already cached
//         if (Cache::has($cacheKey)) {
//             // Retrieve the cached data
//             $cachedResponse = Cache::get($cacheKey);
//             $sharedDetails = $cachedResponse['shared_details'];
//             $tickets = $cachedResponse['tickets'];
//         } else {
//             // The data is not cached, proceed with the API request
//             $url = "http://127.0.0.1:7500/verify-bulkTicket-payment";

//             try {
//                 $paystackResponse = Http::withHeaders([
//                     'Content-Type' => 'application/json',
//                     'Accept' => 'application/json'
//                 ])->post($url, [
//                     'reference' => $reference
//                 ]);

//                 if ($paystackResponse->successful()) {
//                     $paymentData = $paystackResponse->json();
                
//                     Cache::put($cacheKey, $paymentData, now()->addHour());

//                     $sharedDetails = $paymentData['shared_details'];
//                     $tickets = $paymentData['tickets'];
                    
//                     $matchday = Matchday::firstOrCreate(
//                         [
//                             'home_team' => $sharedDetails['home_team'],
//                             'away_team' => $sharedDetails['away_team'],
//                             'match_date' => $sharedDetails['match_date'],
//                             'price' => $sharedDetails['price'],
//                         ]
//                     );
                    
//                     foreach ($tickets as $ticket) {
//                         VendorTicket::firstOrCreate(
//                             [
//                                 'ticket_code' => $ticket['ticket_code'],
//                                 'vendor_id' => $request->user()->id,
//                                 'matchday_id' => $matchday->id,
//                                 'status' => 'pending',
//                             ]
//                         );
//                     }
//                 } else {
//                     return redirect()->route('failurePage')->with('error', 'Failed to verify payment :=>' . $paystackResponse);
//                 }        

//             } catch (\Exception $e) {
//                 return redirect()->route('failurePage')->with('error', 'Error verifying payment: ' . $e->getMessage());
//             }
//         }

//         return redirect()->route('dashboard');
//     }

// }

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $matchdays = $this->ticketService->get('/matchdays');
        return view('tickets.index', ['matchdays' => $matchdays]);
    }

    public function show($id)
    {
        $matchdayData = $this->ticketService->get("/matchdays/{$id}");
        $recent_stats = collect($this->ticketService->get('/matchdays/recent-matchdays'));
        return view('tickets.details', compact('recent_stats', 'matchdayData'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'home_team' => 'required|string|max:255',
            'away_team' => 'required|string|max:255',
            'match_time' => 'required|date',
            'number_of_tickets' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $requestData = [
            'home_team' => $validatedData['home_team'],
            'away_team' => $validatedData['away_team'],
            'match_time' => $validatedData['match_time'],
            'number_of_tickets' => $validatedData['number_of_tickets'],
            'price' => $validatedData['price'],
        ];

        $response = $this->ticketService->post('/matchdays', $requestData);

        if ($response) {
            return redirect()->route('tickets.index')->with('success', 'Matchday and Tickets created successfully!');
        } else {
            return redirect()->back()->withErrors('Failed to create matchday');
        }
    }

    public function showMuseumTickets()
    {
        $museumTickets = $this->ticketService->get("/tickets/get-museum-tickets")['available_tickets'];
        return view('orders.museum-tickets', compact('museumTickets'));
    }

    public function showSeasonDetails()
    {
        $seasonDetails = $this->ticketService->get("/get-season-details");
        $seasonTickets = $this->ticketService->get("/get-season-tickets")['season_tickets'];
        return view('tickets.season-ticket', compact('seasonDetails', 'seasonTickets'));
    }

    public function updateSeasonDetails(Request $request)
    {
        $validatedData = $request->validate([
            'seasonYear' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'endDate' => 'required|date',
        ]);

        $requestData = [
            'season_year' => $validatedData['seasonYear'],
            'price' => $validatedData['price'],
            'end_date' => $validatedData['endDate'],
        ];

        $response = $this->ticketService->post('/update-season-details', $requestData);

        if ($response) {
            return redirect()->route('season.ticket')->with('success', 'Season updated successfully!');
        } else {
            return redirect()->back()->withErrors('Failed to update season');
        }
    }

    public function verifyBulkTicketPayment(Request $request)
    {
        $reference = $request->input('reference');
        $cacheKey = 'paystack-verification-' . $reference;

        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);
            $sharedDetails = $cachedResponse['shared_details'];
            $tickets = $cachedResponse['tickets'];
        } else {
            $paymentData = $this->ticketService->post('/verify-bulkTicket-payment', ['reference' => $reference]);

            if ($paymentData) {
                Cache::put($cacheKey, $paymentData, now()->addHour());

                $sharedDetails = $paymentData['shared_details'];
                $tickets = $paymentData['tickets'];

                $matchday = Matchday::firstOrCreate([
                    'home_team' => $sharedDetails['home_team'],
                    'away_team' => $sharedDetails['away_team'],
                    'match_date' => $sharedDetails['match_date'],
                    'price' => $sharedDetails['price'],
                ]);

                foreach ($tickets as $ticket) {
                    VendorTicket::firstOrCreate([
                        'ticket_code' => $ticket['ticket_code'],
                        'vendor_id' => $request->user()->id,
                        'matchday_id' => $matchday->id,
                        'status' => 'pending',
                    ]);
                }
            } else {
                return redirect()->route('failurePage')->with('error', 'Failed to verify payment');
            }
        }

        return redirect()->route('dashboard');
    }
}