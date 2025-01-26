<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title')
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>


    {{-- <link rel='stylesheet' href="build/assets/app-C7s3BGlm.css">
    <link rel='stylesheet' href="build/assets/app-nIy0u6Ly.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="build/assets/app-BuVCY1-9.js"></script>
    <script src="build/assets/bootstrap.esm-qSNUocBa.js"></script>
    <script src="build/assets/util-BvXE9BZe.js"></script> --}}

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/util.js'])
    {{-- <link rel="preload" as="style" href="http://localhost:8080/build/assets/app-nIy0u6Ly.css">
    <link rel="preload" as="style" href="http://localhost:8080/build/assets/app-C7s3BGlm.css">
    <link rel="modulepreload" href="http://localhost:8080/build/assets/app-BuVCY1-9.js">
    <link rel="modulepreload" href="http://localhost:8080/build/assets/bootstrap.esm-qSNUocBa.js">
    <link rel="modulepreload" href="http://localhost:8080/build/assets/util-BvXE9BZe.js">
    <link rel="stylesheet" href="http://localhost:8080/build/assets/app-nIy0u6Ly.css">
    <link rel="stylesheet" href="http://localhost:8080/build/assets/app-C7s3BGlm.css">
    <script type="module" src="http://localhost:8080/build/assets/app-BuVCY1-9.js"></script>
    <script type="module" src="http://localhost:8080/build/assets/util-BvXE9BZe.js"></script> --}}

</head>
<script>
    var spinner =
        '<div class="spinner-border spinner-border-sm me-2" role="status"><span class="visually-hidden">Loading...</span></div>';
</script>

<body>

    @auth
        <x-nav />
    @endauth
    <main>
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 99999;">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto toast-header-text"></strong>
                    <small class="toast-header-time"></small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                </div>
            </div>
        </div>
        @yield('main')
    </main>

</body>

</html>
