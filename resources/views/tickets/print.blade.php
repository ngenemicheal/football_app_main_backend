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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
        }

        .qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border-radius: 15px;
            background-color: #e3f2fd;
        }

        .details {
            text-align: left;
            flex: 1;
        }

        .details h4 {
            margin: 0 0 10px;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .details p {
            margin: 5px 0;
            font-size: 16px;
            font-weight: 400;
            color: #555;
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

        .download-btn{
           position: absolute;
           bottom: 20px;
           background-color: #007bff;
           color: white;
           border: none;
           padding: 10px 20px;
           border-radius: 5px;
           cursor: pointer;
       }

       .download-btn:hover {
           background-color: #0056b3;
       }
    </style>
</head>
<body>

    <div id="ticket-container" class="container">
        <img src="{{ asset('images/5176.png') }}" alt="Rangers Logo" class="club-logo">

        <div class="qr-code">
            {!! QrCode::size(150)->generate($ticket['ticket_code']) !!}
        </div>

        <div class="details">
            <p>Match: {{ $ticket['home_team'] }} vs {{ $ticket['away_team'] }}</p>
            <p>Date: {{ \Carbon\Carbon::parse($ticket['match_date'])->format('d M Y, g:i A') }}</p>
            <p>Price: ₦ {{ $ticket['price'] }}</p>
            <p>Venue: Rangers Stadium</p> 
        </div>
    </div>
    {{-- <button class="download-btn" onclick="goToDashboard()">Go to Dashboard</button> --}}
    {{-- <button style="display: none" class="download-btn" onclick="downloadPDF()">Download as PDF</button> --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(downloadPDF, 500);
        });

        function downloadPDF() {
            var element = document.getElementById('ticket-container');
            var opt = {
                margin:       0.1,
                filename:     'match_ticket.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: [9, 2.9], orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save()
            .then(() => {
                window.location.href = '/dashboard';
            });
        }
    </script>
</body>
</html>
