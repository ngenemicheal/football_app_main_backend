<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Season Tickets') }}
            </h2>

            <button id="openModalBtn" class="text-blue-600 hover:text-blue-800 visited:text-purple-600 transition duration-150 ease-in-out">
                @if ($seasonDetails['status'] === 'success')
                    <i class="fa-solid fa-pen-to-square"></i> Edit Season Ticket
                @else
                    <i class="fa-solid fa-plus"></i> Create Season Ticket
                @endif
            </button>
        </div>
    </x-slot>

    <!-- Season Ticket Details Card -->
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col gap-4">
                <h3 class="text-2xl font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-ticket-alt text-blue-500"></i> Current Season Ticket Details
                </h3>
                <div class="flex items-center justify-between text-gray-700 dark:text-gray-300">
                    <span>Season: </span> 
                    <strong>{{ $seasonDetails['seasonYear'] ?? null }}</strong>
                </div>
                <div class="flex items-center justify-between text-gray-700 dark:text-gray-300">
                    <span>Price: </span>
                    <strong>₦{{ number_format($seasonDetails['price'], 2) ?? null }}</strong>
                </div>
                <div class="flex items-center justify-between text-gray-700 dark:text-gray-300">
                    <span>End Date: </span> 
                    <strong>{{ \Carbon\Carbon::parse($seasonDetails['endDate'])->format('d M Y') ?? null }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- List of Already Bought Season Tickets -->
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight pb-5">Already Bought Season Tickets</h2>
        <div class="overflow-auto max-h-96">
            <table class="min-w-full table-auto text-center text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Name</th>
                        <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Email</th>
                        <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Price</th>
                        <th class="px-6 py-3 font-medium text-gray-600 dark:text-gray-300">Expiry Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @if(empty($seasonTickets))
                        <tr>
                            <td colspan="4" class="text-center">No tickets bought yet.</td>
                        </tr>
                    @else
                        @foreach ($seasonTickets as $ticket)
                            <tr>
                                <td class="px-6 py-4">{{ $ticket['ticket_holder'] }}</td>
                                <td class="px-6 py-4">{{ $ticket['email'] }}</td>
                                <td class="px-6 py-4">{{ $ticket['price'] }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($ticket['expiry_date'])->format('d M Y, g:i A') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Season Ticket Modal -->
    <div id="seasonLongTicketModal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal Card -->
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <!-- Modal Header -->
                    <div class="bg-white dark:bg-gray-700 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-500 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-edit fa-xl text-green-500 dark:text-green-100"></i>
                            </div>
                            <div class="mt-5 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">Edit Season Ticket</h3>
                            </div>
                        </div>

                        <!-- Modal Form -->
                        <div class="mt-4 max-w-2xl mx-auto">
                            <form method="POST" action="{{ route('season.tickets.update') }}" class="space-y-4">
                                @csrf

                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ticket Price (₦)</label>
                                    <input 
                                        name="price" 
                                        type="number" 
                                        step="0.01" 
                                        value="{{ $seasonDetails['price'] }}" 
                                        placeholder="Ticket Price (₦)" 
                                        required 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    />
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="seasonYear" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Season Year</label>
                                    <input 
                                        name="seasonYear" 
                                        type="text" 
                                        value="{{ $seasonDetails['seasonYear'] }}" 
                                        placeholder="2021/2022" 
                                        required 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    />
                                    <x-input-error :messages="$errors->get('seasonYear')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                    <input 
                                        name="endDate" 
                                        type="date" 
                                        value="{{ \Carbon\Carbon::parse($seasonDetails['endDate'])->format('Y-m-d') }}" 
                                        required 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    />
                                    <x-input-error :messages="$errors->get('endDate')" class="mt-2" />
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="mt-6 w-full text-center bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                                    {{ __('Update Season Ticket') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse">
                        <button 
                            id="closeModalBtn" 
                            type="button" 
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-500 sm:w-auto transition duration-150 ease-in-out"
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
        const modal = document.getElementById('seasonLongTicketModal');
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

