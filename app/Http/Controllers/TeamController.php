<?php

namespace App\Http\Controllers;

use App\Models\VendorTicket;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Spatie\Async\Pool;
use Illuminate\Support\Facades\Http;

class TeamController extends Controller
{

    protected $teamService;
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function showTeamDetails(Request $request)
    {
        $vendorId = $request->user()->id;
        $role = $request->user()->role;
    
        if ($role === 'super') {

            $pool = Pool::create();
    
            $pool->add(function () {
                return $this->teamService->getPlayers();
            })->then(function ($result) use (&$players) {
                $players = $result;
            });
    
            $pool->add(function () {
                return $this->teamService->getPastFixtures();
            })->then(function ($result) use (&$pastFixtures) {
                $pastFixtures = $result;
            });
    
            $pool->add(function () {
                return $this->teamService->getUpcomingFixtures();
            })->then(function ($result) use (&$upcomingFixtures) {
                $upcomingFixtures = $result;
            });
    
            $pool->add(function () {
                return $this->teamService->getLeagueTable();
            })->then(function ($result) use (&$leagueData) {
                $leagueData = $result;
            });
    
            $pool->wait();
    
            return view('dashboard', [
                'players' => $players,
                'pastFixtures' => $pastFixtures,
                'upcomingFixtures' => $upcomingFixtures,
                'leagueTable' => $leagueData['standings'] ?? [],
                'leagueLogo' => $leagueData['leagueInfo']['leagueLogo'] ?? null,
                'leagueFlag' => $leagueData['leagueInfo']['leagueFlag'] ?? null,
                'leagueSeason' => $leagueData['leagueInfo']['leagueSeason'] ?? null,
            ]);
    
        } else {

            $tickets = VendorTicket::where('vendor_id', $vendorId)
                                   ->where('status', 'pending')
                                   ->with('matchday')
                                   ->get();
    
            if ($tickets->isNotEmpty()) {
                $ticketDetails = $tickets->map(function ($ticket) {
                    return [
                        'ticket_code' => $ticket->ticket_code,
                        'status' => $ticket->status,
                        'home_team' => $ticket->matchday->home_team,
                        'away_team' => $ticket->matchday->away_team,
                        'match_date' => $ticket->matchday->match_date,
                        'price' => $ticket->matchday->price,
                    ];
                });
            }
    
            return view('dashboard', [
                'tickets' => $ticketDetails ?? null,
            ]);
        }
    }
    
    public function buyTickets()
    {
        $matchdayID = null;
        $ticketPrice = 0;

        $pool = Pool::create();

        $pool->add(function () {
            VendorTicket::where('status', 'printed')->delete();
        });

        $pool->add(function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get(env('TICKET_SERVICE_URL') . "/api/v1/get-matchday");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        })->then(function ($matchdayData) use (&$matchdayID, &$ticketPrice) {
            if ($matchdayData) {
                $matchdayID = $matchdayData['matchdayId'] ?? null;
                $ticketPrice = $matchdayData['ticketPrice'] ?? 0;
            }
        });

        $pool->wait();

        return view('buy-tickets', [
            'matchdayID' => $matchdayID,
            'ticketPrice' => $ticketPrice,
        ]);
    }

}