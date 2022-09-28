@extends('admin.layouts.master')
@section('title', 'Department')

@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
<!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="company-form">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="department_name">Name <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="department_name" name="department_name" placeholder="Enter name..." autocomplete="off">
                        <div class="invalid-feedback" id="valid-name"></div>
                    </div>
                    <div class="form-group">
                        <label for="manager">Chief Department <sup class="text-danger">*</sup></label>
                        <select class="form-control select1" id="manager" name="manager" style="width: 100%;">
                            <option value="">Choose user</option>
                        </select>
                        <!-- <div class="invalid-feedback" id="valid-day"></div> -->
                    </div>
                    <div class="form-group">
                        <label for="location_id">Location <sup class="text-danger">*</sup></label>
                        <select class="form-control select2" id="location_id" name="location_id" style="width: 100%;">
                            <option value="">Choose location</option>
                        </select>
                        <div class="invalid-feedback" id="valid-location"></div>
                    </div>
                    <div class="form-group">
                        <label for="name">Notes </label>
                        <input type="text" class="form-control" id="notes" name="notes" placeholder="Enter notes..." autocomplete="off">

                    </div>
                </form>

            </div>
            <div class="modal-footer no-bd">
                <button type="button" id="btn-close" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Close
                </button>
                <button type="button" id="btn-save" class="btn btn-info">
                    <i class="fas fa-check"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Department Data</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    Department List
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card card-info">
                <div class="card-header">
                    <button class="btn btn-info ml-auto" id="btn-add">
                        <i class="fas fa-plus-circle"></i>
                        Create Data
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="user-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Chief Department</th>
                                    <!-- <th>Working day</th> -->
                                    <th>Location</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
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
        $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{url('admin/department')}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    data: 'chief',
                    name: 'chief'
                },
                // {
                //     data: 'workday.name',
                //     name: 'workday.name'
                // },
                {
                    data: 'location.name',
                    name: 'location.name'
                },
                {
                    data: 'notes',
                    name: 'notes'
                },
                {
                    "data": null,
                    render: function(data, type, row) {
                        return `<div>  <button data-toggle="tooltip" data-original-title="Edit" data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-toggle="tooltip" data-original-title="Delete" data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                    }
                },
                // {
                //     data: 'action',
                //     name: 'action',
                //     className: 'text-center',
                //     orderable: false,
                //     searchable: false
                // }
            ],
        });

        $('#user-table').DataTable().on('draw', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Open Modal to Add new Category
        $('#btn-add').click(function() {
            $('#formModal').modal('show');
            $.ajax({
                type: "GET",
                url: "{{url('admin/department/componet')}}",
                // data:"",
                dataType: "json",
                success: function(response) {
                    // console.log(response.data);
                    console.log(response);
                    if (response.status == 404) {
                        toastr.error(response.message);

                    } else {

                        // console.log(response.work)
                        var selOpts = "";
                        for (i=0;i<response.user.length;i++)
                        {
                            console.log(response.user[i]['name']);
                            var id = response.user[i]['id'];
                            var val = response.user[i]['name'];
                            selOpts += "<option value='"+id+"'>"+val+"</option>";
                        }
                        $('#manager').append(selOpts);

                        // timetable
                        var time = "";
                        console.log(response.location)
                        for (i = 0; i < response.location.length; i++) {
                            console.log(response.location[i]['name']);
                            var id = response.location[i]['id'];
                            var val = response.location[i]['name'];
                            // var val = response.location[i]['name'] +">"+ "from" + " "+ response.location[i]['on_duty_time']+" " +"to"+" "+ response.location[i]['off_duty_time'];
                            time += "<option value='" + id + "'>" + val + "</option>";
                        }
                        $('#location_id').append(time);
                    }
                }
            });
            $('.modal-title').html('Create Data');
            $('#company-form').trigger('reset');
            $('#btn-save').html('<i class="fas fa-check"></i> Save Data');
            $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
            $('#btn-save').val('save').removeAttr('disabled');
            $('.close').click(function() {
                // remove that from select value after save data to avoid dublicate data
                // console.log('close button');
                $('#location_id').find('option').remove().end().append('<option value="">Choose location</option>').val('');
                $('#manager').find('option').remove().end().append('<option value="">Chooose user</option>').val('');
            })
            $('#btn-close').click(function() {
                // remove that from select value after save data to avoid dublicate data
                console.log('close button');
                $('#location_id').find('option').remove().end().append('<option value="">Choose location</option>').val('');
                $('#manager').find('option').remove().end().append('<option value="">Chooose user</option>').val('');
            })
        });

        // Store new company or update company
        $('#btn-save').click(function() {
            // save data state
            var formData = {
                department_name: $('#department_name').val(),
                manager: $('#manager').val(),
                location_id: $('#location_id').val(),
                notes: $('#notes').val(),
            };
            var state = $('#btn-save').val();
            // console.log(formData['workday_id'])

            var type = "POST";
            var ajaxurl = "{{url('admin/department/store')}}";
            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            console.log(state);
            console.log(ajaxurl);
            // update state
            if (state == "update") {
                console.log(state);
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                var id = $('#id').val();
                type = "PUT";
                ajaxurl = "{{ url('admin/department/update') }}" + '/' + id;
                console.log(ajaxurl);
            }

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    console.log('save sinit');
                    console.log(data);
                    if (state == "save") {
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
                        if(data.code ==0){
                            swal({
                                title: "Success!",
                                text: data.message,
                                icon: "success",
                                timer: 3000
                            });
                        }else{
                            swal({
                                title: "Error!",
                                text: data.message,
                                icon: "error",
                                timer: 3000
                            });
                        }
                       

                        $('#user-table').DataTable().draw(false);
                        $('#user-table').DataTable().on('draw', function() {
                            $('[data-toggle="tooltip"]').tooltip();
                        });
                    }
                    $('#location_id').find('option').remove().end().append('<option value="">Chooose location</option>').val('');
                    $('#manager').find('option').remove().end().append('<option value="">Chooose user</option>').val('');
                    $('#formModal').modal('hide');
                },
                error: function(data) {
                    try {
                        if (state == "save") {
                            console.log(data.responseJSON.errors.department_name)
                            // console.log(data.responseJSON.errors.workday_id)
                            console.log(data.responseJSON.errors.location_id)
                            if (data.responseJSON.errors.department_name) {
                                $('#department_name').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-name').html(data.responseJSON.errors.department_name);
                            }
                            // if (data.responseJSON.errors.workday_id) {
                            //     $('#workday_id').removeClass('is-valid').addClass('is-invalid');
                            //     $('#valid-day').removeClass('valid-feedback').addClass('invalid-feedback');
                            //     $('#valid-day').html(data.responseJSON.errors.workday_id);
                            // }
                            if (data.responseJSON.errors.location_id) {
                                $('#location_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-location').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-location').html(data.responseJSON.errors.location_id);
                            }


                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } else {
                            if (data.responseJSON.errors.department_name) {
                                $('#department_name').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-name').html(data.responseJSON.errors.department_name);
                            }
                            // if (data.responseJSON.errors.workday_id) {
                            //     $('#workday_id').removeClass('is-valid').addClass('is-invalid');
                            //     $('#valid-day').removeClass('valid-feedback').addClass('invalid-feedback');
                            //     $('#valid-day').html(data.responseJSON.errors.workday_id);
                            // }
                            if (data.responseJSON.errors.location_id) {
                                $('#location_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-location').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-location').html(data.responseJSON.errors.location_id);
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
                                text: "An error occurred, please try again",
                                icon: "error",
                                timer: 3000
                            });
                        }

                        $('#formModal').modal('hide');
                    }
                }
            });
        });
        //  Edit Category
        $(document).on('click', '#editBtn', function() {
            var id = $(this).attr('data-id');
            console.log(id);
            $.get("{{ url('admin/department/edit') }}" + '/' + id, function(data) {
                $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                // show data on modal
                // $('#name').val(data.nip);
                $('#id').val(data.data.id);
                $('#department_name').val(data.data.department_name);
                $('#manager').val(data.data.manager);
                $('#notes').val(data.data.notes);
                // console.log(data);
                // console.log(data.data);
                // console.log(data.mywork[0].name);
                console.log(data.mylocation[0].name);
                var selOpts = "";
                $.each(data.user, function(key, value) {
                                       
                    selOpts=   $('select[name="manager"]').append(`<option value="${value.id}" ${value.id == data.data.manager? 'selected' : ''}>${value.name}</option>`)});
                                    // $('.editModal').append(selOpts);
                $('#formModal').find('#manager').append(selOpts)
                //                       // timetable
                var time = "";


                $.each(data.mylocation, function(key, value) {
                    time = $('select[name="location_id"]')
                        .append(`<option value="${value.id}" ${value.id == data.data.location_id ? 'selected' : ''}>${value.name}</option>`)
                    

                });
                // $('#location_id').append(time);
                $('#formModal').find('#location_id').append(time);

                // change value button save to update then state to save
                $('#btn-save').val('update').removeAttr('disabled');
                $('#formModal').modal('show');
                $('.modal-title').html('Edit Data');
                $('#null').html('<small id="null">Kosongkan jika tidak ingin di ubah</small>');
                $('#btn-save').html('<i class="fas fa-check"></i> Edit');
            $('.close').click(function() {
                // remove that from select value after save data to avoid dublicate data
                // console.log('close button');
                $('#location_id').find('option').remove().end().append('<option value="">Choose location</option>').val('');
                $('#manager').find('option').remove().end().append('<option value="">Chooose user</option>').val('');
            })
            $('#btn-close').click(function() {
                // remove that from select value after save data to avoid dublicate data
                console.log('close button');
                $('#location_id').find('option').remove().end().append('<option value="">Choose location</option>').val('');
                $('#manager').find('option').remove().end().append('<option value="">Chooose user</option>').val('');
            })
            }).fail(function() {
                swal({
                    title: "Sorry!",
                    text: "Failed to update data!",
                    icon: "error",
                    timer: 3000
                });
            });
        });



        // Delete company
        $(document).on('click', '#deleteBtn', function() {
            var id = $(this).attr('data-id');
            console.log(id);
            swal("Warning!", "Are you sure you want to delete?", "warning", {
                buttons: {
                    cancel: "NO!",
                    ok: {
                        text: "Yes!",
                        value: "ok"
                    }
                },
            }).then((value) => {
                switch (value) {
                    case "ok":
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('admin/department/delete') }}" + '/' + id,
                            success: function(data) {
                                if (data.code == 0) {
                                    $('#user-table').DataTable().draw(false);
                                    $('#user-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Success!",
                                        text: data.message,
                                        icon: "success",
                                        timer: 3000
                                    });
                                } else {
                                    swal({
                                        title: "Sorry!",
                                        text: data.message,
                                        icon: "error",
                                        timer: 3000
                                    });
                                }

                            },
                            error: function(data) {
                                swal({
                                    title: "Sorry!",
                                    text: "An error occurred, please try again",
                                    icon: "error",
                                    timer: 3000
                                });
                            }
                        });
                        break;

                    default:
                        swal({
                            title: "Oh Ya!",
                            text: "Data is not change",
                            icon: "info",
                            timer: 3000
                        });
                        break;
                }
            });
        });
    });
</script>
@endsection