<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/modules/bootstrap/css/bootstrap.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/npic-favicon.ico') }}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>@yield('code')</h1>
                        <div class="page-description">
                            @yield('message')
                        </div>
                        <div class="page-search">
                            <div class="mt-3">
                                <a href="{{ url('/') }}">Back to Home</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simple-footer mt-5">
                    Copyright &copy; Stisla 2018
                </div>
            </div>
        </section>
    </div>
</body>

</html>
