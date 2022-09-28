@extends('admin.layouts.master')
@section('title', 'Change Password')

@section('content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Change User Password</h1>
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
                            <form method="POST" action="{{ url('admin/user/update-password') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Employee <sup class="text-danger">*</sup></label>
                                                <select class="form-control select2 @error('user_id') is-invalid @enderror" id="user_id" name="user_id" style="width: 100%;">
                                                    <option value="">Choose employee</option>
                                                            @foreach($employees as $employee)
                                                                <option value="{{ $employee->id }}" >{{ $employee->name }}</option>
                                                            @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-user_id">{{ $errors->first('user_id') }}</div>
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    <div class="invalid-feedback" id="valid-password">{{ $errors->first('password') }}</div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control  @error('password_confirmation') is-invalid @enderror"" id="password_confirmation" name="password_confirmation">
                                    <div class="invalid-feedback" id="valid-password_confirmation">{{ $errors->first('password_confirmation') }}</div>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // key up function on form password
            $('body').on('keyup', '#user_id, #password, #password_confirmation', function() {
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
            // $('#btn-save').click(function() {
                
            // });
            // $('form').submit(function() {
            //     $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            //     var formData = {
            //         user_id: $('#user_id').val(),
            //         password: $('#password').val(),
            //         password_confirmation: $('#password_confirmation').val(),
                    
            //     };
            //     var type = "POST";
            //     var ajaxurl = "{{url('admin/user/update-password')}}";
            //     $.ajax({
            //         type: type,
            //         url: ajaxurl,
            //         data: formData,
            //         dataType: 'json',
            //         success: function(data) {
            //             console.log(state);
            //             swal({
            //                     title: "Success!",
            //                     text: "Data has been added successfully!",
            //                     icon: "success",
            //                     timer: 3000
            //                 });

            //                 $('#user-table').DataTable().draw(false);
            //                 $('#user-table').DataTable().on('draw', function() {
            //                     $('[data-toggle="tooltip"]').tooltip();
            //                 });
            //         },
            //         error: function(data) {
            //             try {
            //                 if (data.responseJSON.errors.user_id) {
            //                         $('#user_id').removeClass('is-valid').addClass('is-invalid');
            //                         $('#valid-user_id').removeClass('valid-feedback').addClass('invalid-feedback');
            //                         $('#valid-user_id').html(data.responseJSON.errors.user_id);
            //                     }
            //                     console.log(data.responseJSON.errors.password)
            //                     if (data.responseJSON.errors.password) {
            //                         $('#password').removeClass('is-valid').addClass('is-invalid');
            //                         $('#valid-password').removeClass('valid-feedback').addClass('invalid-feedback');
            //                         $('#valid-password').html(data.responseJSON.errors.password);
            //                     }
            //                     if (data.responseJSON.errors.password_confirmation) {
            //                         $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
            //                         $('#valid-password_confirmation').removeClass('valid-feedback').addClass('invalid-feedback');
            //                         $('#valid-password_confirmation').html(data.responseJSON.errors.password_confirmation);
            //                     }


            //                     $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
            //                     $('#btn-save').removeAttr('disabled');
                        
            //             } catch {
            //                 swal({
            //                         title: "Sorry!",
            //                         text: "An error occurred, please try again",
            //                         icon: "error",
            //                         timer: 3000
            //                     });
            //             }
            //         }
            //     });
               
            // });
        });
    </script>
@endsection