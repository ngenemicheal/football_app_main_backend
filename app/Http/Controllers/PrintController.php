<?php

namespace App\Http\Controllers;

use App\Models\VendorTicket;
use Illuminate\Http\Request;

class PrintController extends Controller
{

    public function printTicket(Request $request)
    {
        $validatedData = $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = VendorTicket::where('ticket_code', $validatedData['ticket_code'])
                              ->with('matchday')
                              ->first();
    
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }
    
        $ticketData = [
            'ticket_code' => $ticket->ticket_code,
            'home_team' => $ticket->matchday->home_team,
            'away_team' => $ticket->matchday->away_team,
            'match_date' => $ticket->matchday->match_date,
            'price' => $ticket->matchday->price,
        ];

        $ticket->status = 'printed';
        $ticket->save();
    
        return view('tickets.print', ['ticket' => $ticketData]);
    }
    
    public function printTickets(Request $request)
    {

        $validatedData = $request->validate([
            'ticket_count' => 'required|integer|min:1',
        ]);

        $tickets = VendorTicket::where('vendor_id', $request->user()->id)
                                ->where('status', 'pending')
                                ->take($validatedData['ticket_count'])
                                ->get();

        if ($tickets->count() < $validatedData['ticket_count']) {
            return response()->json(['error' => 'Not enough available tickets'], 400);
        }

        $sharedDetails = [
            'home_team' => $tickets->first()->matchday->home_team,
            'away_team' => $tickets->first()->matchday->away_team,
            'match_date' => $tickets->first()->matchday->match_time,
            'price' => $tickets->first()->matchday->price,
        ];

        foreach ($tickets as $ticket) {
            $ticket->status = 'printed';
            $ticket->save();

            $ticketDetails[] = [
                'ticket_code' => $ticket->ticket_code,
            ];
        }
    
        return view('tickets.multiple-print', [
            'sharedDetails' => $sharedDetails,
            'tickets' => $ticketDetails,
            'tickets_per_page' => 4
        ]);
    }

    public function paymentError()
    {
        return view('payment-error');
    }
}