<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vendor Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="flex flex-col space-y-10">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden">
                    <div class="p-8 text-gray-900 dark:text-gray-100">
                        @if($matchdayID == null)
                            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 text-center items-center">
                                <p class="text-gray-700 dark:text-gray-300 font-bold text-xl">No available matchday.</p>
                            </div>
                        @else
                            <h3 class="text-2xl font-semibold mb-6">{{ __('Buy Tickets in Bulk') }}</h3>
                            <form id="bulkTicketForm" class="space-y-6">
                                <!-- Ticket Count -->
                                <div class="relative">
                                    <label for="ticket_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Number of Tickets
                                    </label>
                                    <input id="ticket_count" name="ticket_count" type="number" min="1" class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Enter the number of tickets" required>
                                </div>

                                <!-- Vendor Name -->
                                <div class="relative">
                                    <label for="vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Vendor Name
                                    </label>
                                    <input 
                                        id="vendor" 
                                        name="vendor" 
                                        type="text" 
                                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                        value="{{ Auth::user()->name }}"
                                        required
                                        readonly
                                    >
                                </div>

                                <div class="relative">
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email Name
                                    </label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="text" 
                                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                        value="{{ Auth::user()->email }}"
                                        required
                                        readonly
                                    >
                                </div>

                                <div class="relative">
                                    <label for="matchday_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Matchday ID
                                    </label>
                                    <input 
                                        id="matchday_id" 
                                        name="matchday_id" 
                                        type="number" 
                                        class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                        value="{{ $matchdayID }}"
                                        required
                                        readonly
                                    >
                                </div>

                                <div class="relative">
                                    <label for="total_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Total Price
                                    </label>
                                    <input id="total_price" name="total_price" type="text" readonly class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm sm:text-sm" placeholder="Total price will be calculated" required>
                                </div>

                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                                    Buy Tickets
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    // Set the price per ticket
    const ticketPrice = {{ $ticketPrice }};

    // Calculate the total price automatically when the ticket count changes
    document.getElementById('ticket_count').addEventListener('input', function() {
        const ticketCount = this.value;
        const totalPrice = ticketCount * ticketPrice;

        document.getElementById('total_price').value = totalPrice.toFixed(2);
    });

    const bulkTicketForm = document.getElementById('bulkTicketForm');
        
        bulkTicketForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const vendor = document.getElementById('vendor').value;
            const numberOfTickets = document.getElementById('ticket_count').value;
            const matchdayId = document.getElementById('matchday_id').value;
            const price = document.getElementById('total_price').value;
            
            const requestData = {
                vendor_email: email,
                price: price,
                matchday_id: matchdayId,
                vendor_name: vendor,
                number_of_tickets: numberOfTickets
            };
    
            const url = "http://127.0.0.1:7500/api/intialize-bulkTicket-payment";
    
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.authorization_url) {
                    window.location.href = data.authorization_url;
                } else {
                    alert('Failed to initialize payment: ' + data.error);
                    console.log(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error occurred while processing payment.');
            });
        });
</script>
