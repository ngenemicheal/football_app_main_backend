<x-app-layout>
    <x-slot name="header">
        @if (Auth::user()->role === 'super')
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        @else
            <div class="flex justify-between">
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Vendor Dashboard') }}
                </h2>
                <button id="openModalBtn" class="text-blue-600 visited:text-purple-600">
                    Print Multiple Tickets
                </button>
            </div>
        @endif
    </x-slot>

    <div class="flex flex-col space-y-10">

        @if (Auth::user()->role === 'super')

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex flex-row space-x-3 text-center">
                                <h3 class="text-xl font-semibold mb-4">Team Players</h3>
                            </div>
                            <div class="overflow-auto max-h-96">
                                <table class="min-w-full table-auto text-left text-sm">
                                    <thead class="bg-gray-100 dark:bg-gray-700 text-center">
                                        <tr>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Photo</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Name</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Position</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Age</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Nationality</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-center">
                                        @if(empty($players))
                                            <tr>
                                                <td colspan="6" class="text-center">No players available at the moment.</td>
                                            </tr>
                                        @else
                                            @foreach ($players as $player)
                                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 items-center">
                                                    <td class="px-6 py-4">
                                                        <img class="w-12 h-12 rounded-full" src="{{ $player['photo'] }}" alt="{{ $player['name'] }} photo">
                                                    </td>
                                                    <td class="px-6 py-4">{{ $player['name'] }}</td>
                                                    <td class="px-6 py-4">{{ $player['position'] }}</td>
                                                    <td class="px-6 py-4">{{ $player['age'] }}</td>
                                                    <td class="px-6 py-4">{{ $player['nationality'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-xl font-semibold mb-4">{{ __('Past Fixtures') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center">
                                @foreach ($pastFixtures as $fixture)
                                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                                        <div class="p-6">
                                            <div class="flex items-center justify-center space-x-4">
                                                <!-- Home Team -->
                                                <div class="text-center">
                                                    <img src="{{ $fixture['home_logo'] }}" alt="{{ $fixture['home_team'] }} logo" class="w-12 h-12 mx-auto mb-2">
                                                    <p class="font-bold">{{ $fixture['home_team'] }}</p>
                                                </div>
                                                <!-- vs Text -->
                                                <div class="text-center">
                                                    <p class="text-lg font-bold">vs</p>
                                                </div>
                                                <!-- Away Team -->
                                                <div class="text-center">
                                                    <img src="{{ $fixture['away_logo'] }}" alt="{{ $fixture['away_team'] }} logo" class="w-12 h-12 mx-auto mb-2">
                                                    <p class="font-bold">{{ $fixture['away_team'] }}</p>
                                                </div>
                                            </div>
            
                                            <!-- Score -->
                                            <p class="text-lg text-gray-900 dark:text-gray-100 font-bold mt-4">
                                                {{ $fixture['home_goals'] }} - {{ $fixture['away_goals'] }}
                                            </p>
            
                                            <!-- Match Date and Venue -->
                                            <p class="mt-2 text-gray-700 dark:text-gray-300">Date: {{ \Carbon\Carbon::parse($fixture['date'])->format('d M Y, g:i A') }}</p>
                                            <p class="text-gray-700 dark:text-gray-300">Venue: {{ $fixture['venue'] }}</p>
            
                                            <!-- Status (e.g., FT for full-time) -->
                                            <p class="text-gray-700 dark:text-gray-300">Status: {{ $fixture['status'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-xl font-semibold mb-4">{{ __('Upcoming Matches') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center">
                                @foreach ($upcomingFixtures as $fixture)
                                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                                        <div class="p-6">
                                            <div class="flex items-center justify-center space-x-4">
                                                <!-- Home Team -->
                                                <div class="text-center">
                                                    <img src="{{ $fixture['home_logo'] }}" alt="{{ $fixture['home_team'] }} logo" class="w-12 h-12 mx-auto mb-2">
                                                    <p class="font-bold">{{ $fixture['home_team'] }}</p>
                                                </div>
                                                <!-- vs Text -->
                                                <div class="text-center">
                                                    <p class="text-lg font-bold">vs</p>
                                                </div>
                                                <!-- Away Team -->
                                                <div class="text-center">
                                                    <img src="{{ $fixture['away_logo'] }}" alt="{{ $fixture['away_team'] }} logo" class="w-12 h-12 mx-auto mb-2">
                                                    <p class="font-bold">{{ $fixture['away_team'] }}</p>
                                                </div>
                                            </div>
            
                                            <!-- Match Date and Venue -->
                                            <p class="mt-2 text-gray-700 dark:text-gray-300">Date: {{ \Carbon\Carbon::parse($fixture['date'])->format('d M Y, g:i A') }}</p>
                                            <p class="text-gray-700 dark:text-gray-300">Venue: {{ $fixture['venue'] }}</p>
            
                                            <!-- Status (e.g., FT for full-time) -->
                                            <p class="text-gray-700 dark:text-gray-300">Status: {{ $fixture['status'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="flex flex-row space-x-3 text-center">
                                <img class="w-6 h-6" src="{{ $leagueLogo }}" alt="NPFL League Logo">
                                <h3 class="text-xl font-semibold mb-4">NPFL League Table {{$leagueSeason}}</h3>
                                <img class="w-6 h-6" src="{{ $leagueFlag }}" alt="NPFL League Flag">
                            </div>
                            <div class="overflow-auto max-h-96">
                                <table class="min-w-full table-auto text-left text-sm">
                                    <thead class="bg-gray-100 dark:bg-gray-700 text-center">
                                        <tr>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Position</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Team</th>

                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">PL</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">W</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">D</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">L</th>

                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">GF</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">GA</th>
                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">GA</th>

                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">PTS</th>

                                            <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Form</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-center">
                                        @if(empty($leagueTable))
                                            <tr>
                                                <td colspan="4" class="text-center">No teams available at the moment.</td>
                                            </tr>
                                        @else
                                            @foreach ($leagueTable as $team)
                                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 items-center">
                                                    <td class="px-6 py-4">{{ $team['rank'] }}</td>
                                                    <td class="px-6 py-4 flex items-center">
                                                        <img class="w-8 h-8 rounded-full mr-3" src="{{ $team['teamLogo'] }}" alt="{{ $team['teamName'] }} logo">
                                                        <span>{{ $team['teamName'] }}</span>
                                                    </td>

                                                    <div class="space-x-1">
                                                        <td class="px-6 py-4">{{ $team['totalGamesPlayed'] }}</td>
                                                        <td class="px-6 py-4">{{ $team['totalGamesWon'] }}</td>
                                                        <td class="px-6 py-4">{{ $team['totalGamesDrawn'] }}</td>
                                                        <td class="px-6 py-4">{{ $team['totalGamesLost'] }}</td>
                                                    </div>

                                                    <div class="space-x-2">
                                                        <td class="px-6 py-4">{{ $team['goalsFor'] }}</td>
                                                        <td class="px-6 py-4">{{ $team['goalsAgainst'] }}</td>
                                                        <td class="px-6 py-4">{{ $team['goalsDiff'] }}</td>
                                                    </div>

                                                    <td class="px-6 py-4">{{ $team['points'] }}</td>

                                                    <td class="px-6 py-4 flex items-center space-x-1">
                                                        @foreach (str_split($team['form']) as $letter)
                                                            <span class="inline-block w-5 h-5 text-white font-bold text-center rounded items-center"
                                                                @if ($letter === 'W')
                                                                    style="background-color: rgb(13, 180, 13);"
                                                                @elseif ($letter === 'D')
                                                                    style="background-color: yellow; color: black;"
                                                                @elseif ($letter === 'L')
                                                                    style="background-color: red;"
                                                                @endif
                                                            >
                                                                {{ $letter }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                        @endif                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else

            <div class="py-4">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-900 shadow-md rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            @if($tickets != null)
                                <h3 class="text-xl font-semibold mb-4">
                                    {{ __('Available Tickets: ') }} {{ $tickets->count() }}
                                </h3>
                            @endif
                            @if($tickets == null)
                                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden p-6 text-center items-center">
                                    <p class="text-gray-700 dark:text-gray-300 font-bold text-center">No available tickets at the moment.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center">
                                    @foreach ($tickets as $ticket)
                                        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                                            <div class="p-6">
                                                <div class="flex items-center justify-center space-x-4">
                                                    <div class="text-center">
                                                        <p class="">{{ $ticket['home_team'] }}</p>
                                                    </div>

                                                    <div class="text-center">
                                                        <p class="text-lg font-bold">vs</p>
                                                    </div>

                                                    <div class="text-center">
                                                        <p class="">{{ $ticket['away_team'] }}</p>
                                                    </div>
                                                </div>

                                                <p class="text-gray-900 dark:text-gray-100 mt-4">
                                                    Ticket Code: {{ $ticket['ticket_code'] }}
                                                </p>

                                                <p class="mt-2 text-gray-700 dark:text-gray-300">
                                                    Match Date: {{ \Carbon\Carbon::parse($ticket['match_date'] )->format('d M Y, g:i A') }}
                                                </p>

                                                <p class="text-gray-700 dark:text-gray-300">
                                                    Price: {{ $ticket['price'] }}
                                                </p>

                                                <form method="POST" action="{{ url('/print-ticket') }}" id="printTicket">
                                                    @csrf
                                                    <input type="hidden" name="ticket_code" value="{{ $ticket['ticket_code'] }}">
                                                </form>

                                                <button 
                                                    class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded print-ticket"
                                                    onclick="document.getElementById('printTicket').submit();"
                                                >
                                                    Print
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @endif

    </div>


    @if (Auth::user()->role === 'vendor-admin' && $tickets != null)
        <div id="matchdayModal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal Container -->
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <!-- Modal Card -->
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        
                        <!-- Modal Header -->
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fa-solid fa-plus fa-xl text-green-500"></i>
                                </div>
                                <div class="mt-5 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Print Multiple Tickets</h3>
                                </div>
                            </div>

                            <div class="mt-4 max-w-2xl mx-auto">
                                <form method="POST" action="{{ url('/print-tickets') }}" class="space-y-4">
                                    @csrf
                                    <input 
                                        name="ticket_count" 
                                        id="ticket_count" 
                                        type="number" 
                                        min="1" 
                                        max="{{$tickets->count()}}"
                                        placeholder="Number of Tickets" 
                                        required 
                                        class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    />
                                    <input 
                                        name="vendor" 
                                        id="vendor" 
                                        type="text"
                                        required 
                                        value="{{ Auth::user()->name }}"
                                        class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    />

                                    <button type="submit" class="mt-6 w-full text-center bg-green-400 hover:bg-green-500 text-white font-medium py-2 px-4 rounded-md shadow-sm">{{ __('Print') }}</button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                            <button 
                                id="closeModalBtn" 
                                type="button" 
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-500 sm:w-auto"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-app-layout>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Get modal and buttons
        const modal = document.getElementById('matchdayModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');

        // Open modal
        openModalBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });

        // Close modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Optionally, close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

</script>