<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
        }

        .container {
            background: linear-gradient(135deg, #f0efef, #f1f1f1);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 850px;
            width: 90%;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
            position: relative;
            margin-bottom: 20px;
        }

        .club-logo {
            position: absolute;
            top: 20px;
            right: 20px;
            max-width: 70px;
            height: auto;
        }

        .details {
            text-align: left;
            flex: 1;
        }

        .details h5 {
            margin: 0 0 10px;
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }

        .details p {
            font-size: 16px;
            font-weight: 400;
            color: #555;
            margin: 5px 0;
        }

        .ticket-code {
            color: #28a745;
            font-weight: 600;
        }

        .expires {
            color: #f44336;
            font-weight: 600;
        }

        .club-logo {
            position: absolute;
            top: 20px;
            right: 20px;
            max-width: 70px;
            height: auto;
        }

        .qr-code img {
            max-width: 150px;
            height: auto;
        }

        .page {
            page-break-after: always;
        }

        .ticket-wrapper {
            max-width: 850px;
            margin: auto;
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="ticket-wrapper">
        @php
            $ticketsChunked = collect($tickets)->chunk($tickets_per_page);
        @endphp

        @foreach ($ticketsChunked as $chunk)
            <div class="page">
                @foreach ($chunk as $ticket)
                    <div id="ticket-container" class="container">
                        <!-- Club Logo -->
                        <img src="{{ asset('images/5176.png') }}" alt="Rangers Logo" class="club-logo">
                
                        <!-- QR Code -->
                        <div class="qr-code">
                            {!! QrCode::size(150)->generate($ticket['ticket_code']) !!}
                        </div>
                
                        <!-- Ticket Details -->
                        <div class="details">
                            <p>Match: {{ $sharedDetails['home_team'] }} vs {{ $sharedDetails['away_team'] }}</p>
                            <p>Date: {{ \Carbon\Carbon::parse($sharedDetails['match_date'])->format('d M Y, g:i A') }}</p>
                            <p>Price: ₦ {{ $sharedDetails['price'] }}</p>
                            <p>Venue: Rangers Stadium </p> 
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(downloadPDF, 500);
        });

        function downloadPDF() {
            var element = document.querySelector('.ticket-wrapper');
            var opt = {
                margin: 0.1,
                filename: 'match_tickets.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save()
            .then(() => {
                window.location.href = '/dashboard';
            });
        }
    </script>
</body>
</html>
