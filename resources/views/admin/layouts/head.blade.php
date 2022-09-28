<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ asset('img/logo.jpeg') }}">
<title>Admin @yield('title')</title>


<!-- General CSS Files -->
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('backend/css/all.min.css') }}"> -->
<!-- <link rel="stylesheet" href="https://demo.getstisla.com/assets/modules/bootstrap/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="https://demo.getstisla.com/assets/modules/fontawesome/css/all.min.css">

@yield('css')

<!-- Template CSS -->
<link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/components.css') }}">
<!-- <link rel="stylesheet" href="https://demo.getstisla.com/assets/css/style.css"> -->
<!-- <link rel="stylesheet" href="https://demo.getstisla.com/assets/css/components.css"> -->
