<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>Confirm Password</title>

        <!-- General CSS Files -->
        <link rel="stylesheet" href="{{ asset('backend/modules/bootstrap/css/bootstrap.min.css') }}">

        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
        <link rel="icon" href="{{ asset('img/npic-favicon.ico') }}">
    </head>

    <body>
        <div id="app">
            <section class="section">
                <div class="container mt-5">
                    <div class="row">
                        <div
                            class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                            <div class="login-brand">
                                <img src="{{ asset('backend/img/stisla-fill.svg') }}" alt="logo" width="100"
                                    class="shadow-light rounded-circle">
                            </div>

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Confirm Password</h4>
                                </div>

                                <div class="card-body">
                                    <p class="text-muted">Please confirm your password before continuing.</p>
                                    <form method="POST" action="{{ route('password.confirm') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" tabindex="1" autocomplete="current-password" autofocus>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                                Confirm Password
                                            </button>

                                            <br>

                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="simple-footer">
                                Copyright &copy; Stisla 2018
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>

</html>
