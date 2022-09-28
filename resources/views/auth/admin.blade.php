<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>Login</title>

        <!-- General CSS Files -->
        <link rel="stylesheet" href="{{ asset('backend/modules/bootstrap/css/bootstrap.min.css') }}">

        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    </head>

    <body>
        <div id="app">
            <section class="section">
                <div class="container mt-5">
                    <div class="row">
                        <div
                            class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                            <div class="login-brand">
                                <br>Login</font>
                            </div>

                            <div class="card card-primary">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="login">Username or Email</label>
                                            <input id="login" type="text" class="form-control {{ $errors->has('username') || $errors->has('email') ? 'is-invalid' : '' }}"
                                            value="{{ old('username') ?: old('email') }}" name="login" tabindex="1"
                                                autofocus>
                                                @if ($errors->has('username') || $errors->has('email'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                                </div>
                                                @endif
                                        </div>

                                        <div class="form-group">
                                            <div class="d-block">
                                                <label for="password" class="control-label">Password</label>
                                            </div>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" tabindex="2" autocomplete="current-password">
                                                @error('password')
                                                <div class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                                Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="simple-footer">
                                Copyright &copy; 2021 by Renny Suryani
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>

</html>
