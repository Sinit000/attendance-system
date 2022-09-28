@extends('admin.layouts.master')
@section('title', 'Contract')

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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Ref Code </label>
                                <input type="text" class="form-control" id="ref_code" name="ref_code" placeholder="Enter ref_code.." autocomplete="off">
                                <div class="invalid-feedback" id="valid-ref_code"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="workday_id">Employee name <sup class="text-danger">*</sup></label>
                                <select class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                                    <option value="">Choose employee</option>
                                </select>
                                <div class="invalid-feedback" id="valid-user_id"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Structure <sup class="text-danger">*</sup></label>
                                <select class="form-control select2" id="structure_id" name="structure_id" style="width: 100%;">
                                    <option value="">Choose structure</option>

                                </select>
                                <div class="invalid-feedback" id="valid-structure_id"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Working_schedule </label>
                                <input type="text" class="form-control" id="working_schedule" name="working_schedule" placeholder="Enter working_schedule.." autocomplete="off">
                                <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Start date <sup class="text-danger">*</sup></label>
                                <div class="bootstrap-timepicker ">
                                    <input type="date" class="form-control datepicker" id="start_date" name="start_date">
                                </div>
                                <div class="invalid-feedback" id="valid-start_date"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">End date <sup class="text-danger">*</sup></label>
                                <div class="bootstrap-timepicker ">
                                    <input type="date" class="form-control datepicker" id="end_date" name="end_date">
                                </div>
                                <div class="invalid-feedback" id="valid-end_date"></div>
                            </div>
                        </div>
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
<!--  -->

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Contract</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    Contract list
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
                        <table class="table table-sm table-hover" id="user-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Ref Code </th>
                                    <th>Name </th>
                                    <th>Structure </th>
                                    <th>Start Date </th>
                                    <th>End Date </th>
                                    <th>Working Schedule</th>
                                    <th>Status</th>
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
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            ajax: "{{url('admin/contract')}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ref_code',
                    name: 'ref_code'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'structure.name',
                    name: 'structure.name'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
                },
                {
                    data: 'working_schedule',
                    name: 'working_schedule'
                },
                {
                    data: 'status',
                    name: 'status'
                },

                // status=="pending"? {
                //     "data": null,
                //     render: function(data, type, row) {
                //         return `<div>  <button data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                //     }
                // }:
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

            // $("#company-form")[0].reset();
            $.ajax({
                type: "GET",
                url: "{{url('admin/contract/componet')}}",
                // data:"",
                dataType: "json",
                success: function(response) {
                    console.log(response.data);
                    console.log(response);
                    if (response.status == 404) {


                    } else {

                        console.log(response.data)
                        var selOpts = "";
                        for (i = 0; i < response.data.length; i++) {
                            console.log(response.data[i]['name']);
                            var id = response.data[i]['id'];
                            var val = response.data[i]['name'];
                            selOpts += "<option value='" + id + "'>" + val + "</option>";

                        }

                        $('#user_id').append(selOpts);
                        // timetable
                        var time = "";
                       
                        for (i = 0; i < response.structure.length; i++) {
                            console.log(response.structure[i]['name']);

                            var id = response.structure[i]['id'];
                            var val = response.structure[i]['name'];
                            var base = response.structure[i]['base_salary'];
                           
                            console.log(id)
                            // var val = response.location[i]['name'] +">"+ "from" + " "+ response.location[i]['on_duty_time']+" " +"to"+" "+ response.location[i]['off_duty_time'];
                            time += "<option value='" + id + "'>" + val + " Salary " + base +  "</option>";
                        }
                        $('#structure_id').append(time);

                    }
                }
            });
            $('.modal-title').html('Create Data');
            $('#company-form').trigger('reset');
            $('#btn-save').html('<i class="fas fa-check"></i> Save Data');

            $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
            $('#btn-save').val('save').removeAttr('disabled');
            // if click close button
            $('.close').click(function() {
                // remove that from select value after save data to avoid dublicate data
                console.log('close button');
                $('#user_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');
                $('#structure_id').find('option').remove().end().append('<option value="">Chooose structure</option>').val('');

            })
            $('#btn-close').click(function() {
                // remove that from select value after save data to avoid dublicate data
                console.log('close button');
                $('#user_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');
                $('#structure_id').find('option').remove().end().append('<option value="">Chooose structure</option>').val('');

            })
        });


        // Store new company or update company
        $('#btn-save').click(function() {
            // save data state
            var formData = {
                ref_code: $('#ref_code').val(),
                user_id: $('#user_id').val(),
                structure_id: $('#structure_id').val(),
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                working_schedule: $('#working_schedule').val(),


            };

            var state = $('#btn-save').val();


            var type = "POST";
            var ajaxurl = "{{url('admin/contract/store')}}";
            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            console.log(state);
            console.log(ajaxurl);
            // update state
            if (state == "update") {
                
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                var id = $('#id').val();
                type = "PUT";
                ajaxurl = "{{ url('admin/contract/update') }}" + '/' + id;
                console.log(ajaxurl);
            }

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    console.log(state);
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
                        // $("#company-form")[0].reset();
                    } else {
                        console.log(state);
                        swal({
                            title: "Success!",
                            text: "Data has been updated successfully!",
                            icon: "success",
                            timer: 3000
                        });

                        $('#user-table').DataTable().draw(false);
                        $('#user-table').DataTable().on('draw', function() {
                            $('[data-toggle="tooltip"]').tooltip();
                        });

                    }
                    // remove that from select value after save data to avoid dublicate data
                    $('#structure_type_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');

                    $('#formModal').modal('hide');
                },
                error: function(data) {

                    try {
                        if (state == "save") {
                            if (data.responseJSON.errors.ref_code) {
                                $('#ref_code').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-ref_code').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-ref_code').html(data.responseJSON.errors.ref_code);
                            }
                            if (data.responseJSON.errors.user_id) {
                                $('#user_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-user_id').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-user_id').html(data.responseJSON.errors.user_id);
                            }
                            if (data.responseJSON.errors.structure_id) {
                                $('#structure_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-structure_id').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-structure_id').html(data.responseJSON.errors.structure_id);
                            }
                            if (data.responseJSON.errors.start_date) {
                                $('#start_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-start_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-start_date').html(data.responseJSON.errors.start_date);
                            }
                            if (data.responseJSON.errors.end_date) {
                                $('#end_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-end_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-end_date').html(data.responseJSON.errors.end_date);
                            }
                            if (data.responseJSON.errors.working_schedule) {
                                $('#working_schedule').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-working_schedule').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-working_schedule').html(data.responseJSON.errors.working_schedule);
                            }


                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } else {
                            if (data.responseJSON.errors.ref_code) {
                                $('#ref_code').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-ref_code').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-ref_code').html(data.responseJSON.errors.ref_code);
                            }
                            if (data.responseJSON.errors.user_id) {
                                $('#user_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-user_id').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-user_id').html(data.responseJSON.errors.user_id);
                            }
                            if (data.responseJSON.errors.structure_id) {
                                $('#structure_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-structure_id').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-structure_id').html(data.responseJSON.errors.structure_id);
                            }
                            if (data.responseJSON.errors.start_date) {
                                $('#start_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-start_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-start_date').html(data.responseJSON.errors.start_date);
                            }
                            if (data.responseJSON.errors.end_date) {
                                $('#end_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-end_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-end_date').html(data.responseJSON.errors.end_date);
                            }
                            if (data.responseJSON.errors.working_schedule) {
                                $('#working_schedule').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-working_schedule').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-working_schedule').html(data.responseJSON.errors.working_schedule);
                            }


                            $('#btn-save').html('<i class="fas fa-check"></i> Update');
                            $('#btn-save').removeAttr('disabled');
                        }
                    } catch {
                        console.log('eror');
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
            $.get("{{ url('admin/contract/edit') }}" + '/' + id, function(data) {
                $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                // show data on modal
                // $('#name').val(data.nip);
                $('#id').val(data.data.id);
                $('#ref_code').val(data.data.ref_code);
                $('#user_id').val(data.data.user_id);
                $('#structure_id').val(data.data.structure_id);
                $('#start_date').val(data.data.start_date);
                $('#end_date').val(data.data.end_date);
                $('#working_schedule').val(data.data.working_schedule);
               



                var selOpts = "";
                $.each(data.user, function(key, value) {
                    // selOpts=  `<option value="${value.id}" ${value.id == response.data['workday_id'] ? 'selected' : ''}>${value.name}</option>`;
                    selOpts = $('select[name="user_id"]').append(`<option value="${value.id}" ${value.id == data.data.user_id? 'selected' : ''}>${value.name}</option>`)
                });
                // $('.editModal').append(selOpts);
                $('#formModal').find('#user_id').append(selOpts)
                //                       // timetable
                var structure = "";
                $.each(data.structure, function(key, value) {
                    // selOpts=  `<option value="${value.id}" ${value.id == response.data['workday_id'] ? 'selected' : ''}>${value.name}</option>`;
                    structure = $('select[name="structure_id"]').append(`<option value="${value.id}" ${value.id == data.data.structure_id? 'selected' : ''}>${value.name} ${value.base_salary}</option>`)
                });
                // $('.editModal').append(selOpts);
                $('#formModal').find('#structure_id').append(structure)




                // change value button save to update then state to save
                $('#btn-save').val('update').removeAttr('disabled');
                $('#formModal').modal('show');
                $('.modal-title').html('Edit Data');
                $('#null').html('<small id="null">Kosongkan jika tidak ingin di ubah</small>');
                $('#btn-save').html('<i class="fas fa-check"></i> Edit');
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
                            url: "{{ url('admin/contract/delete') }}" + '/' + id,
                            success: function(data) {
                                if (data.code == 0) {
                                    $('#user-table').DataTable().draw(false);
                                    $('#user-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Success!",
                                        text: "Data has been deleted successfully!",
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