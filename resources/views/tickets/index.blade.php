<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Matchday Page') }}
            </h2>
            <button id="openModalBtn" class="text-blue-600 visited:text-purple-600">
                Create a Matchday
            </button>
        </div>
    </x-slot>

    
        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            @if(count($matchdays) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($matchdays as $matchday)
                            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                <!-- Content Section -->
                                <div class="p-6">
                                    <div class="flex justify-between">
                                        <i class="fa-solid fa-futbol fa-lg"></i>
                                        @if (auth()->user()->role == 'super')
                                            <x-dropdown>
                                                <x-slot name="trigger">
                                                    <button>
                                                        <i class="fa-solid fa-ellipsis" style="color: #000000;"></i>
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <!-- Details Button -->
                                                    <x-dropdown-link :href="route('tickets.show', $matchday['id'])">
                                                    {{-- <x-dropdown-link> --}}
                                                        {{ __('Details') }}
                                                    </x-dropdown-link>

                                                </x-slot>
                                            </x-dropdown>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-lg text-gray-900 font-bold">{{ $matchday['home_team'] }} vs {{ $matchday['away_team'] }}</p>
                                        <p class="mt-2 text-gray-700">Time: {{ \Carbon\Carbon::parse($matchday['match_time'])->format('d M Y, g:i A') }}</p>
                                        <p class="mt-2 text-gray-700">Price per ticket: ₦{{ $matchday['price'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                </div>
            @else
                <div class="flex items-center justify-center">
                    <div class="text-center items-center">
                        <i class="fas fa-futbol fa-4x text-gray-500 mb-4"></i>
                        <p class="text-3xl font-bold text-gray-700">
                            No matchdays available.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    
    <!-- Matchday Modal -->
    <div id="matchdayModal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
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
                                <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Create a New Matchday</h3>
                            </div>
                        </div>

                        <!-- Modal Form -->
                        <div class="mt-4 max-w-2xl mx-auto">
                            <form method="POST" action="{{ route('tickets.store') }}" class="space-y-4">
                                @csrf
                                <!-- Home Team -->
                                <input 
                                    name="home_team" 
                                    type="text" 
                                    placeholder="Home Team" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('home_team')" class="mt-2" />

                                <!-- Away Team -->
                                <input 
                                    name="away_team" 
                                    type="text" 
                                    placeholder="Away Team" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('away_team')" class="mt-2" />

                                <!-- Match Time -->
                                <input 
                                    name="match_time" 
                                    type="datetime-local" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('match_time')" class="mt-2" />

                                <!-- Number of Tickets -->
                                <input 
                                    name="number_of_tickets" 
                                    type="number" 
                                    min="1" 
                                    placeholder="Number of Tickets" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('number_of_tickets')" class="mt-2" />

                                <!-- Ticket Price -->
                                <input 
                                    name="price" 
                                    type="number" 
                                    step="0.01" 
                                    placeholder="Ticket Price (₦)" 
                                    required 
                                    class="block w-full px-3 py-2 border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />

                                <!-- Submit Button -->
                                <button type="submit" class="mt-6 w-full text-center bg-green-400 hover:bg-green-500 text-white font-medium py-2 px-4 rounded-md shadow-sm">{{ __('Create Matchday') }}</button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Footer -->
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
