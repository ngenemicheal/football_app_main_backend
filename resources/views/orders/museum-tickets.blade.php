<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Museum Tickets Page') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-gray-500 dark:text-gray-400 text-center">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6">Name</th>
                        <th scope="col" class="py-3 px-6">Email</th>
                        <th scope="col" class="py-3 px-6">Ticket Type</th>
                        <th scope="col" class="py-3 px-6">Total Price</th>
                        <th scope="col" class="py-3 px-6">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($museum_tickets as $museum_ticket)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <td class="py-4 px-6">{{ $museum_ticket['buyer_name'] }}</td>
                        <td class="py-4 px-6">{{ $museum_ticket['buyer_email'] }}</td>
                        <td class="py-4 px-6">{{ $museum_ticket['ticket_type'] }}</td>
                        <td class="py-4 px-6">{{ $museum_ticket['amount'] }}</td>
                        <td class="py-4 px-6">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $museum_ticket['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($museum_ticket['status']) }} <!-- Status from API -->
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>                
            </table>
            

            <!-- Pagination links -->
            {{-- <div class="mt-4">
                {{ $orders->links() }}
            </div> --}}
        </div>
    </div>
</x-app-layout>

