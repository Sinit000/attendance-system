<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Login Page</title>

    <!--Bootsrap 4 CDN-->
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

    <!--Custom styles-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/login.css') }}">
    <link rel="icon" href="{{ asset('img/npic-favicon.ico') }}">

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend\plugins\fontawesome-free-5.0.1\css\fontawesome-all.css') }}">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Login</h3>

                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text"
                                class="form-control {{ $errors->has('username') || $errors->has('email') ? 'is-invalid' : '' }}"
                                name="login" placeholder="username or email"
                                value="{{ old('username') ?: old('email') }}" autofocus>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="password" name="password" autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Login" class="btn float-right btn-warning">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
