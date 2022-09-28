@extends('admin.layouts.master')
@section('title', 'Change Password')

@section('content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Change Password</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-lock"></i>
                    Change Password
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {!! session('success') !!}
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="card card-info">
                        <div class="card-body">
                            <form method="POST" action="{{ url('admin/update-password') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                    <div class="invalid-feedback" id="valid-current_password">{{ $errors->first('current_password') }}</div>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    <div class="invalid-feedback" id="valid-password">{{ $errors->first('password') }}</div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    <div class="invalid-feedback" id="valid-password_confirmation"></div>
                                </div>
                                <button type="submit" class="btn btn-info btn-round float-right" id="btn-save">
                                    <i class="fas fa-check"></i>
                                    Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
    <script>
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $(document).ready(function() {
            // key up function on form password
            $('body').on('keyup', '#current_password, #password, #password_confirmation', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });
            $('form').submit(function() {
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            });
        });
    </script>
@endsection