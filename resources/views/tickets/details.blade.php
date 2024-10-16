<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Matchday Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-6 bg-white shadow-md mt-0">
        <!-- Matchday Info -->
        <h3 class="text-xl font-bold mb-6">{{ $matchdayData['matchday']['home_team'] }} vs {{ $matchdayData['matchday']['away_team'] }}</h3>
        
        <div class="mb-4">
            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full 
                {{ $matchdayData['matchday']['status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $matchdayData['matchday']['status'] ? 'Active' : 'Closed' }}
            </span>
        </div>

        <p><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($matchdayData['matchday']['match_time'])->format('d M Y, g:i A') }}</p>
        <p><strong>Price per Ticket:</strong> ₦ {{ number_format($matchdayData['matchday']['price'], 2) }}</p>
        <p><strong>Total Tickets Available:</strong> {{ $matchdayData['matchday']['number_of_tickets'] }}</p>
        <p><strong>Tickets Remaining:</strong> {{ $matchdayData['unsold_tickets'] }}</p>


        <!-- Table to list sales data (by vendor) -->
        <div class="mt-8 shadow-xl rounded-xl overflow-hidden">
            <h4 class="text-lg font-bold p-6">Ticket Sales Breakdown</h4>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Vendor</th>
                        <th class="px-4 py-2">Tickets Sold</th>
                        <th class="px-4 py-2">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matchdayData['vendors'] as $vendor)
                        <tr class="text-center">
                            <td class="border px-4 py-2">{{ $vendor['vendor_name'] }}</td>
                            <td class="border px-4 py-2">{{ $vendor['tickets_sold'] }}</td>
                            <td class="border px-4 py-2">₦ {{ number_format($vendor['tickets_sold'] * $matchdayData['matchday']['price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Graph Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
            <!-- Pie Chart: Sales Distribution -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h4 class="text-lg font-bold mb-4">Ticket Sales Distribution</h4>
                <canvas id="salesPieChart" width="100" height="100"></canvas>
            </div>

            <!-- Bar Chart: Total Tickets and Tickets Sold per Matchday -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h4 class="text-lg font-bold mb-4">Total Tickets and Tickets Sold per Matchday</h4>
                <canvas id="matchdayBarChart" width="100" height="100"></canvas>
            </div>
        </div>

    </div>

</x-app-layout>

<script>

var salesPieCtx = document.getElementById('salesPieChart').getContext('2d');

// Function to generate random colors
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Generate dynamic colors for each vendor
var vendorCount = {{ count($matchdayData['vendors']) }};
var backgroundColors = [];

for (var i = 0; i < vendorCount; i++) {
    backgroundColors.push(getRandomColor());
}

var salesPieChart = new Chart(salesPieCtx, {
    type: 'pie',
    data: {
        labels: [@foreach ($matchdayData['vendors'] as $vendor) '{{ $vendor['vendor_name'] }}', @endforeach],
        datasets: [{
            data: [@foreach ($matchdayData['vendors'] as $vendor) {{ $vendor['tickets_sold'] }}, @endforeach],
            backgroundColor: backgroundColors 
        }]
    }
});



// Bar Chart: Total Tickets and Tickets Sold per Matchday
var matchdayBarCtx = document.getElementById('matchdayBarChart').getContext('2d');

var matchdayLabels = @json($recent_stats->pluck('matchday'));
var totalTickets = @json($recent_stats->pluck('total_tickets_created'));
var totalSold = @json($recent_stats->pluck('total_tickets_sold'));

var matchdayBarChart = new Chart(matchdayBarCtx, {
    type: 'bar',
    data: {
        labels: matchdayLabels,
        datasets: [
            {
                label: 'Total Tickets',
                data: totalTickets,
                backgroundColor: '#4caf50'
            },
            {
                label: 'Total Tickets Sold',
                data: totalSold,
                backgroundColor: '#2196f3'
            },
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Tickets'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Matchdays'
                }
            }
        }
    }
});

</script>