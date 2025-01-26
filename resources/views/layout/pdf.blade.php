<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss'])
    <style>
        table th {
            white-space: nowrap;
            font-size: 12px;
        }

        table td {
            font-size: 10px;
        }

        .popover-click {
            text-decoration: none;
        }

        .card-header {
            background: white !important;
            color: #000 !important;
        }

        .logo {
            text-align: center
        }

        .logo img {
            width: 60%;
            margin: 0 auto
        }

        .badge.text-bg-primary {
            background: white !important;
            color: RGBA(var(--bs-primary-rgb), var(--bs-bg-opacity, 1)) !important;
        }

        html {
            -webkit-print-color-adjust: economy;
        }
    </style>

</head>

<body>
    <div class="">
        <div class="logo">
            <img class=" mb-4 " src="{{ public_path() . '/img/acknowledgement_logo.png' }}">
        </div>
        @yield('pdfContent')
    </div>

</body>

</html>
