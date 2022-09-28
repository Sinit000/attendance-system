@extends('admin.layouts.master')
@section('title', 'Employee lists')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')


   <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Employee Data</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-user"></i>
                       Employee List
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="ml-auto">
                            <a href="{{url('admin/user')}}" class="btn btn-outline-primary ">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card">
                            <form id="company-form"  enctype="multipart/form-data"  >
                                @csrf
                                <input type="hidden" name="id" value="{{$data->id}}" id="id">
                                <div class="card-header">
                                <h4>Edit Employees</h4>
                                </div>
                                <div class="card-body">
                                     <!-- row one -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="staff_id">Staff ID </label>
                                                <input type="text" class="form-control" id="no" name="no" value="{{$data->no}}"
                                                    placeholder="Enter staff id..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Full name <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{$data->name}}"
                                                    placeholder="Enter  name..." autocomplete="off">
                                                <div class="invalid-feedback" id="valid-name"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="gender">Gender <sup class="text-danger">*</sup></label>
                                                <select class="form-control select2" id="gender" name="gender" style="width: 100%;">
                                                    <option value="">Choose gender</option>
                                                    <option value="M" {{$data->gender == 'M'?'selected':''}}>Male</option>
                                                    <option value="F" {{$data->gender == 'F'?'selected':''}}>Female</option>
                                                </select>
                                                <div class="invalid-feedback" id="valid-gender"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- row two -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="staff_id">Nationality</label>
                                                <input type="text" class="form-control" id="nationality" name="nationality" value="{{$data->nationality}}"
                                                    placeholder="Enter nationality..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Date of Birth </label>
                                                <div class="bootstrap-timepicker ">
                                                    <input type="date" class="form-control datepicker" id="dob" value="{{$data->dob}}" name="dob">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="staff_id">Card Number</label>
                                                    <input type="text" class="form-control" id="card_number" name="card_number" value="{{$data->card_number}}"
                                                        placeholder="Enter card number..." autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- row three -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Office Tel</label>
                                                <input type="text" class="form-control" id="office_tel" name="office_tel" value="{{$data->office_tel}}"
                                                    placeholder="Enter office tel..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Employee phone</label>
                                                <input type="text" class="form-control" id="employee_phone" name="employee_phone" value="{{$data->employee_phone}}"
                                                    placeholder="Enter employee phone..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" autocomplete="off" placeholder="Enter email....." value="{{$data->email}}">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- row 4 -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Username <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control" id="username" name="username" disabled value="{{$data->username}}"
                                                    placeholder="Enter  username..." autocomplete="off">
                                                <div class="invalid-feedback" id="valid-username"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Password <sup class="text-danger">*</sup></label>
                                                <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Enter Password..." value="{{$data->password}}">
                                                <div class="invalid-feedback" id="valid-password"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Address</label>
                                                <textarea class="form-control" id="address" name="address" value="{{$data->address}}"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- row 5 -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="gender">Position <sup class="text-danger">*</sup></label>
                                                <select class="form-control select2" id="position_id" name="position_id" style="width: 100%;">
                                                    <option value="">Choose position</option>
                                                    @foreach($position as $r)
                                                        <option value="{{$r->id}}" {{$r->id == $data->position_id?'selected':''}}>{{$r->position_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-position"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Department <sup class="text-danger">*</sup></label>
                                                <select class="form-control select2" id="department_id" name="department_id" style="width: 100%;">
                                                    <option value="">Choose Department</option>
                                                    @foreach($department as $r)
                                                        <option value="{{$r->id}}" {{$r->id == $data->department_id?'selected':''}}>{{$r->department_name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-department"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="gender">Profile</label>
                                                <div class="col-md-4">
                                                    <input type="file" name="profile_url" id="profile_url" multiple><br><br>
                                                    <p>
                                                        <img src="{{asset($data->profile_url)}}" alt="150" width="150">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer text-right">
                                    <button type="button" id="btn-save"  class="btn btn-primary">
                                        <i class="fas fa-check"></i>
                                       Update data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initializing DataTable
            $('#btn-save').click(function() {
                var state ="update";
                // save data state
                // var formData = {
                //     name: $('#name').val(),
                //     gender: $('#gender').val(),
                //     position_id: $('#position_id').val(),
                //     department_id: $('#department_id').val(),
                //     profile_url: $('#profile_url')[0].files[0],
                // };
                var fd = new FormData();
                var img= $('#profile_url').val();
                fd.append('no',$('#no').val());
                fd.append('name',$('#name').val());
                fd.append('gender',$('#gender').val());
                fd.append('dob',$('#dob').val());
                fd.append('nationality',$('#nationality').val());
                fd.append('address',$('#address').val());
                fd.append('employee_phone',$('#employee_phone').val());
                fd.append('card_number',$('#card_number').val());
                fd.append('office_tel',$('#office_tel').val());
                fd.append('username',$('#username').val());
                fd.append('password',$('#password').val());
                fd.append('position_id',$('#position_id').val());
                fd.append('department_id',$('#department_id').val());
                if(img){
                    fd.append('profile_url',$('#profile_url')[0].files[0]);
                }else{

                }

                // var state = $('#btn-save').val();

                // var type = "POST";
                // var ajaxurl = "{{url('admin/user/store')}}";
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
                // console.log(state);
                // update state
                if (state == "update") {
                    console.log(state);
                    $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                    var id = $('#id').val();
                    type = "PUT";
                     ajaxurl = "{{ url('admin/user/update') }}" + '/' + id;
                    console.log(ajaxurl);
                }

                $.ajax({
                    mimeType: "multipart/form-data",
                    type: type,
                    url: ajaxurl,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: fd,
                    dataType: 'json',
                    cache : false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(state);
                        if (state == "save") {
                            console.log("success save")
                            swal({
                                title: "Success!",
                                text: "Data has been added successfully!",
                                icon: "success",
                                timer: 3000
                            });

                            $('#user-table').DataTable().draw(false);
                            $('#user-table').DataTable().on('draw', function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        } else {
                            console.log(state);
                            swal({
                                title: "Success!",
                                text: "Data has been updated successfully!",
                                icon: "success",
                                timer: 3000
                            });
                            console.log("success")

                            // $('#user-table').DataTable().draw(false);
                            // $('#user-table').DataTable().on('draw', function() {
                            //     $('[data-toggle="tooltip"]').tooltip();
                            // });
                        }

                        $('#btn-save').html('<i class="fas fa-check"></i> Update Data');
                                $('#btn-save').removeAttr('disabled');
                    },
                    error: function(data) {
                        try {
                            if (state == "save") {
                                console.log("save err");
                                console.log(data.responseJSON.errors.timetable_name)
                                if (data.responseJSON.errors.timetable_name) {
                                    $('#timetable_name').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-name').html(data.responseJSON.errors.timetable_name);
                                }
                                if (data.responseJSON.errors.on_duty_time) {
                                    $('#on_duty_time').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-on_duty_time').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-on_duty_time').html(data.responseJSON.errors.on_duty_time);
                                }
                                if (data.responseJSON.errors.off_duty_time) {
                                    $('#off_duty_time').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-off_duty_time').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-off_duty_time').html(data.responseJSON.errors.off_duty_time);
                                }


                                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                                $('#btn-save').removeAttr('disabled');
                            } else {
                                console.log("update error")
                                console.log(data.responseJSON.errors.gender)
                                console.log(data.responseJSON.errors.name)
                                if (data.responseJSON.errors.name) {
                                    $('#name').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-name').html(data.responseJSON.errors.name);
                                }
                                if (data.responseJSON.errors.gender) {
                                    $('#gender').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-gender').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-gender').html(data.responseJSON.errors.gender);
                                }


                                if (data.responseJSON.errors.position_id) {
                                    $('#position_id').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-position').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-position').html(data.responseJSON.errors.position_id);
                                }
                                if (data.responseJSON.errors.department_id) {
                                    $('#department_id').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-department').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-department').html(data.responseJSON.errors.department_id);
                                }


                                $('#btn-save').html('<i class="fas fa-check"></i> Update');
                                $('#btn-save').removeAttr('disabled');
                            }
                        } catch {
                            if (state == "save") {
                                swal({
                                    title: "Sorry!",
                                    text: "An error occurred, please try again",
                                    icon: "error",
                                    timer: 3000
                                });
                            } else {
                                swal({
                                    title: "Sorry!",
                                    text: "An error occurred, please try again Silahkan coba lagi",
                                    icon: "error",
                                    timer: 3000
                                });
                            }

                            // $('#formModal').modal('hide');
                        }
                    }
                });
            });






        });
    </script>

@endsection
