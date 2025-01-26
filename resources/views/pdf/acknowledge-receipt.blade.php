<html>
<title></title>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;

            font-size: 14px;
        }

        .receiptCon {

            position: relative;
            width: 50%;
        }

        .logo {
            width: 100%;
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

        .w-100 {
            width: 100%
        }

        .text-center {
            text-align: center
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

        .mb-2 {
            margin-bottom: .75rem
        }

        .mb-4 {
            margin-bottom: 1.5rem
        }

        .mb-6 {
            margin-bottom: 2.5rem
        }

        .me-1 {
            margin-right: .5rem
        }

        .me-2 {
            margin-right: .75rem
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

        .d-inline-block {
            display: inline-block
        }
    </style>
</head>

<body>
    <div class="d-flex" style="padding:20px;gap:30px">
        <div class="receiptCon">
            {{-- <img class=" mb-4 logo" src="{{ asset('img/invoice_logo.png') }}"> --}}
            <img class=" mb-4 logo" src="{{ public_path() . '/img/acknowledgement_logo.png' }}">
            <div class="text-center fw-bold mb-4">ACKNOWLEDGEMENT RECEIPT</div>
            <div class=" mb-2">
                <span class="me-2">Date of Payment:</span>
                <span class="fw-bold underline">{{ formattedDate($deposit->deposit_date) }} </span>
            </div>
            <div class="mb-2">
                <span class="me-1">Received from:</span>
                <span class="fw-bold underline"> {{ $deposit->received_from }} </span>
            </div>
            <div class="">
                <span class="me-1">Total Amount:</span>
                <span class="fw-bold underline"> <i>{{ numberSpellOut(number_format($deposit->amount, 2, '.', '')) }}
                    </i></span>
            </div>
            <div class=" mb-2">
                <span class="fw-bold underline">({{ formattedMoney($deposit->amount) }})</span>
            </div>
            <div class="mb-2">
                <span class="me-1 ">Case Title, if applicable:</span>
                <span class="fw-bold underline">{{ $deposit->billing->lawCase->case_title }}</span>
            </div>
            <div class="mb-6">
                <span class="me-1 ">Payment for: </span>
                <span class="fw-bold underline">{{ $deposit->billing->title }}</span>
            </div>

            <div class="mb-2 d-flex">
                <span class="me-1 text-nowrap">Received by: </span>
                <div class="text-center" style="min-width:250px;width:initial">
                    <span
                        class="fw-bold w-100 underline d-inline-block">{{ Str::headline(auth()->user()->name) }}</span>
                    <span class="text-center w-100 d-inline-block"><i>(Signature over printed name)</i></span>
                </div>
            </div>
            <div class="mb-2">
                <span class="me-1 ">Date Received:</span>
                <span class="fw-bold underline">{{ formattedDate($deposit->deposit_date) }} </span>
            </div>
            <div class="mb-2 d-flex">
                <span class="me-1 text-nowrap">Confirmation of OP:</span>
                <span class="fw-bold d-inline-block underline" style="min-width:250px;width:initial"></span>
            </div>
            <div class="mb-6">
                <span class="me-1">Date</span>
                <span class="fw-bold d-inline-block underline" style="min-width:250px;width:initial"></span>
            </div>

            <i>*Please request for Official Receipt for purposes of BIR/taxes
                compliance.</i>
        </div>
        <div class="receiptCon">
            {{-- <img class=" mb-4 logo" src="{{ asset('img/invoice_logo.png') }}"> --}}
            <img class=" mb-4 logo" src="{{ public_path() . '/img/acknowledgement_logo.png' }}">
            <div class="text-center fw-bold mb-4">ACKNOWLEDGEMENT RECEIPT</div>
            <div class=" mb-2">
                <span class="me-2">Date of Payment:</span>
                <span class="fw-bold underline">{{ formattedDate($deposit->deposit_date) }} </span>
            </div>
            <div class="mb-2">
                <span class="me-1">Received from:</span>
                <span class="fw-bold underline"> {{ $deposit->received_from }} </span>
            </div>
            <div class="">
                <span class="me-1">Total Amount:</span>
                <span class="fw-bold underline"> <i>{{ numberSpellOut(number_format($deposit->amount, 2, '.', '')) }}
                    </i></span>
            </div>
            <div class=" mb-2">
                <span class="fw-bold underline">({{ formattedMoney($deposit->amount) }})</span>
            </div>
            <div class="mb-2">
                <span class="me-1 ">Case Title, if applicable:</span>
                <span class="fw-bold underline">{{ $deposit->billing->lawCase->case_title }}</span>
            </div>
            <div class="mb-6">
                <span class="me-1 ">Payment for: </span>
                <span class="fw-bold underline">{{ $deposit->billing->title }}</span>
            </div>

            <div class="mb-2 d-flex">
                <span class="me-1 text-nowrap">Received by: </span>
                <div class="text-center" style="min-width:250px;width:initial">
                    <span
                        class="fw-bold w-100 underline d-inline-block">{{ Str::headline(auth()->user()->name) }}</span>
                    <span class="text-center w-100 d-inline-block"><i>(Signature over printed name)</i></span>
                </div>
            </div>
            <div class="mb-2">
                <span class="me-1 ">Date Received:</span>
                <span class="fw-bold underline">{{ formattedDate($deposit->deposit_date) }} </span>
            </div>
            <div class="mb-2 d-flex">
                <span class="me-1 text-nowrap">Confirmation of OP:</span>
                <span class="fw-bold d-inline-block underline" style="min-width:250px;width:initial"></span>
            </div>
            <div class="mb-6">
                <span class="me-1">Date</span>
                <span class="fw-bold d-inline-block underline" style="min-width:250px;width:initial"></span>
            </div>

            <i>*Please request for Official Receipt for purposes of BIR/taxes
                compliance.</i>
        </div>

    </div>
</body>

</html>
