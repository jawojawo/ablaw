<html>
<title>AB-{{ sprintf('%03d', $billing->id) }}</title>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 24px;
            width: 100%;
        }

        #invCon {
            margin: 0 auto;
            position: relative;
            width: 700px;
        }

        .logo {
            width: 80%;
            display: block;
            margin: 0 auto;
        }

        .fw-bold {
            font-weight: bold;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between
        }

        .text-center {
            text-align: center
        }

        .w-100 {
            width: 100%
        }

        .text-end {
            text-align: right
        }

        .text-start {
            text-align: left;
        }

        .my-4 {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            padding-bottom: 1.5rem
        }

        .mb-6 {
            padding-bottom: 2.5rem
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .underline {
            border-bottom: 1px solid #000;
            width: 100%;

        }
    </style>
</head>

<body>
    <div id="invCon">
        {{-- <img class=" mb-4 logo" src="{{ asset('img/invoice_logo.png') }}"> --}}
        <img class=" mb-4 logo" src="{{ public_path() . '/img/invoice_logo.png' }}">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="fw-bold" style="font-size:18px">Adan Botor and Associates</div>
                <div class="fw-bold" style="font-size:26px">INVOICE</div>
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <div>3rd Floor RAD Bldg.,</div>
                    <div>Elias-Angeles St.,</div>
                    <div>Naga City, Camarines Sur</div>
                </div>
                <div class="text-end">

                    <div>Invoice No.: <span class="fw-bold">AB-{{ sprintf('%03d', $billing->id) }}</span>
                    </div>
                    <div>Date: <span class="fw-bold">{{ formattedDate($billing->billing_date) }}</span>
                    </div>
                    <div>Due Date: <span class="fw-bold">{{ formattedDate($billing->due_date) }}</span>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="mb-4">
                <div class="fw-bold">{{ $billing->client->name }}</div>
                <div>{{ $billing->client->address }}</div>
            </div>
            <div class="mb-4">
                <table class="w-100">
                    <thead class="text-start">
                        <th>Date</th>
                        <th>Description</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </thead>
                    <tbody>
                        <td class="text-nowrap">{{ formattedDate($billing->due_date) }}</td>
                        <td>{{ $billing->title }}</td>
                        <td>{{ formattedMoney($billing->amount) }}</td>
                        <td>{{ formattedMoney($billing->defecit) }}</td>
                    </tbody>
                </table>
            </div>
            <div class="mb-4">
                <div class="text-end">
                    <span class="fw-bold" style="padding-right:30px">TOTAL AMOUNT DUE</span>
                    <span class="fw-bold">{{ formattedMoney($billing->amount) }}</span>
                </div>
            </div>
            <div>
                <div class="mb-6" style="font-size:11px;">
                    Prepared and Verified By:
                </div>
                <div style="display:inline-block;">
                    <div class="underline text-center"
                        style="font-size:11px;line-height:normal;min-width:200px;text-center;display:inline-block;width:initial">
                        {{ Str::headline(auth()->user()->name) }}
                    </div>
                    <div class="text-center" style="font-size:11px;">{{ Str::headline(auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
