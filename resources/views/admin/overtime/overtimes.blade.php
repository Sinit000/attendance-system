@extends('admin.layouts.master')
@section('title', 'Overtime')

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
                                <label for="status">Type <sup class="text-danger">*</sup></label>
                                <select class="form-control select2" id="type" name="type" style="width: 100%;">
                                    <option value="">Choose Type</option>
                                    <option value="hour">hour</option>
                                    <option value="day">day</option>
                                </select>
                                <div class="invalid-feedback" id="valid-type"></div>
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
                                <label for="name">Reason </label>
                                <input type="text" class="form-control" id="reason" name="reason" placeholder="Enter reasons..." autocomplete="off">
                                <div class="invalid-feedback" id="valid-reason"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Duration </label>
                                <input type="text" class="form-control" id="number" name="number" placeholder="Enter duration..." autocomplete="off">
                                <div class="invalid-feedback" id="valid-number"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">From date <sup class="text-danger">*</sup></label>
                                <div class="bootstrap-timepicker ">
                                    <input type="date" class="form-control datepicker" id="from_date" name="from_date">
                                </div>
                                <div class="invalid-feedback" id="valid-from_date"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">To date <sup class="text-danger">*</sup></label>
                                <div class="bootstrap-timepicker ">
                                    <input type="date" class="form-control datepicker" id="to_date" name="to_date">
                                </div>
                                <div class="invalid-feedback" id="valid-to_date"></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">OT Rate </label>
                                <input type="text" class="form-control" id="ot_rate" name="ot_rate" placeholder="Enter ot rate..." autocomplete="off">
                                <div class="invalid-feedback" id="valid-ot_rate"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">OT Hour </label>
                                <input type="text" class="form-control" id="ot_hour" name="ot_hour" placeholder="Enter ot hour..." autocomplete="off">
                                <div class="invalid-feedback" id="valid-ot_hour"></div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">OT Method </label>
                                <input type="text" class="form-control" id="ot_method" name="ot_method" placeholder="Enter ot method..." autocomplete="off">
                                <!-- <div class="invalid-feedback" id="valid-ot_method"></div> -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Notes </label>
                                <input type="text" class="form-control" id="notes" name="notes" placeholder="Enter notes..." autocomplete="off">
                                <!-- <div class="invalid-feedback" id="valid-name"></div> -->
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">pay status </label>
                        <select class="form-control select2" id="pay_status" name="pay_status" style="width: 100%;">

                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
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
            <h1>Overtime</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    Overtime
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
                                    <th><input type="checkbox" name="main_checkbox"><label></label></th>
                                    <th>No</th>
                                    <th>Employee Name </th>
                                    <th>Reason </th>
                                    <th>From Date </th>
                                    <th>Type</th>
                                    <th>Duration</th>
                                    <th>OT Rate</th>
                                    <th>OT hour</th>
                                    <th>Total OT</th>
                                    <th>Request Date </th>
                                    <th>Status </th>

                                    <th>Action <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Send to Accountant</button></th>
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
            ajax: "{{url('admin/overtimes')}}",
            columns: [{
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'reason',
                    name: 'reason'
                },
                {
                    data: 'from_date',
                    name: 'from_date'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'number',
                    name: 'number'
                },
                {
                    data: 'ot_rate',
                    name: 'ot_rate'
                },
                {
                    data: 'ot_hour',
                    name: 'ot_hour'
                },
                {
                    data: 'total_ot',
                    name: 'total_ot'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                // {
                //     "data": null,
                //     render: function(data, type, row) {
                //         return `<div> <input type="checkbox" name="id[]" id="checkIt" data-id="${row.id}" value="${row.id}"/> </div>`
                //     }
                // },
                pay_status == "pending" ? {
                    "data": null,
                    render: function(data, type, row) {
                        return `<div>  <button data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                    }
                } : {
                    "data": null,
                    render: function(data, type, row) {
                        return `<div>  <button data-toggle="tooltip" data-original-title="Edit" data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-toggle="tooltip" data-original-title="Delete" data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                    }
                },
                // {
                //     "data": null,
                //     render: function(data, type, row) {
                //         return data.status == "pending" ? `<div>  <button data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>` : `<div>  <button data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                //     }
                // },
                // {
                //     data: 'action',
                //     name: 'action',
                //     className: 'text-center',
                //     orderable: false,
                //     searchable: false
                // }
            ],
        }).on('draw', function() {
            $('input[name="country_checkbox"]').each(function() {
                this.checked = false;
            });
            $('input[name="main_checkbox"]').prop('checked', false);
            $('button#deleteAllBtn').addClass('d-none');
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
                url: "{{url('admin/overtimes/componet')}}",
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

            })
            $('#btn-close').click(function() {
                // remove that from select value after save data to avoid dublicate data
                console.log('close button');
                $('#user_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');

            })
        });


        // Store new company or update company
        $('#btn-save').click(function() {
            // save data state
            var formData = {
                type: $('#type').val(),
                user_id: $('#user_id').val(),
                reason: $('#reason').val(),
                number: $('#number').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                notes: $('#notes').val(),

                // ot_rate: $('#ot_rate').val(),
                // ot_hour: $('#ot_hour').val(),
                ot_method: $('#ot_method').val(),
                pay_status: $('#pay_status').val(),
            };

            var state = $('#btn-save').val();


            var type = "POST";
            var ajaxurl = "{{url('admin/overtimes/store')}}";
            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            console.log(state);
            console.log(ajaxurl);
            // update state
            if (state == "update") {
                console.log(state);
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                var id = $('#id').val();
                type = "PUT";
                ajaxurl = "{{ url('admin/overtimes/update') }}" + '/' + id;
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
                        if (data.code == -1) {
                            swal({
                                title: "Sorry!",
                                text: data.message,
                                icon: "error",
                                timer: 3000
                            });
                        }
                        if (data == true) {
                            swal({
                                title: "Success!",
                                text: "Data has been added successfully!",
                                icon: "success",
                                timer: 3000
                            });
                        }


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
                    $('#user_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');

                    $('#formModal').modal('hide');
                },
                error: function(data) {

                    try {
                        if (state == "save") {

                            if (data.responseJSON.errors.user_id) {
                                $('#user_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-user_id').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-user_id').html(data.responseJSON.errors.user_id);
                            }
                            if (data.responseJSON.errors.number) {
                                $('#number').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-number').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-number').html(data.responseJSON.errors.number);
                            }
                            if (data.responseJSON.errors.reason) {
                                $('#reason').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-reason').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-reason').html(data.responseJSON.errors.reason);
                            }
                            if (data.responseJSON.errors.number) {
                                $('#number').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-location').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-location').html(data.responseJSON.errors.number);
                            }
                            if (data.responseJSON.errors.from_date) {
                                $('#from_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-from_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-from_date').html(data.responseJSON.errors.from_date);
                            }
                            if (data.responseJSON.errors.to_date) {
                                $('#to_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-to_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-to_date').html(data.responseJSON.errors.to_date);
                            }
                            if (data.responseJSON.errors.type) {
                                $('#type').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-type').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-type').html(data.responseJSON.errors.type);
                            }


                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } else {
                            if (data.responseJSON.errors.user_id) {
                                $('#user_id').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-user_id').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-user_id').html(data.responseJSON.errors.user_id);
                            }
                            if (data.responseJSON.errors.number) {
                                $('#number').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-number').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-number').html(data.responseJSON.errors.number);
                            }
                            if (data.responseJSON.errors.reason) {
                                $('#reason').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-reason').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-reason').html(data.responseJSON.errors.reason);
                            }
                            if (data.responseJSON.errors.number) {
                                $('#number').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-location').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-location').html(data.responseJSON.errors.number);
                            }
                            if (data.responseJSON.errors.from_date) {
                                $('#from_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-from_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-from_date').html(data.responseJSON.errors.from_date);
                            }
                            if (data.responseJSON.errors.to_date) {
                                $('#to_date').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-to_date').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-to_date').html(data.responseJSON.errors.to_date);
                            }
                            if (data.responseJSON.errors.type) {
                                $('#type').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-type').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-type').html(data.responseJSON.errors.type);
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
            $.get("{{ url('admin/overtimes/edit') }}" + '/' + id, function(data) {
                $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                // show data on modal
                // $('#name').val(data.nip);
                $('#id').val(data.data.id);
                $('#type').val(data.data.type);
                $('#reason').val(data.data.reason);
                $('#number').val(data.data.number);
                $('#notes').val(data.data.notes);
                $('#from_date').val(data.data.from_date);
                $('#to_date').val(data.data.to_date);
                $('#user_id').val(data.data.user_id);
                // $('#ot_rate').val(data.data.ot_rate);
                // $('#ot_hour').val(data.data.ot_hour);
                $('#ot_method').val(data.data.ot_method);
                $('#pay_status').val(data.data.pay_status);




                var selOpts = "";
                $.each(data.user, function(key, value) {
                    // selOpts=  `<option value="${value.id}" ${value.id == response.data['workday_id'] ? 'selected' : ''}>${value.name}</option>`;
                    selOpts = $('select[name="user_id"]').append(`<option value="${value.id}" ${value.id == data.data.user_id? 'selected' : ''}>${value.name}</option>`)
                });
                // $('.editModal').append(selOpts);
                $('#formModal').find('#user_id').append(selOpts)
                //                       // timetable
                var time = "";




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
                            url: "{{ url('admin/overtimes/delete') }}" + '/' + id,
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
        // checkBox = document.getElementById('checkIt');
        // var id = $(this).attr('data-id');
        // console.log(id);
        // check box
        $(document).on('click', 'input[name="main_checkbox"]', function() {
            if (this.checked) {
                $('input[name="country_checkbox"]').each(function() {
                    this.checked = true;
                });
            } else {
                $('input[name="country_checkbox"]').each(function() {
                    this.checked = false;
                });
            }
            console.log('show btn delete all');
            toggledeleteAllBtn();
        });

        $(document).on('change', 'input[name="country_checkbox"]', function() {

            if ($('input[name="country_checkbox"]').length == $('input[name="country_checkbox"]:checked').length) {
                $('input[name="main_checkbox"]').prop('checked', true);
            } else {
                $('input[name="main_checkbox"]').prop('checked', false);
            }
            //    remove btn delete all
            console.log('remove btn remove');
            toggledeleteAllBtn();
        });

        //    if click on btn delete
        function toggledeleteAllBtn() {
            if ($('input[name="country_checkbox"]:checked').length > 0) {
                $('button#deleteAllBtn').text('Submit (' + $('input[name="country_checkbox"]:checked').length + ')').removeClass('d-none');
            } else {
                // new classs create in button delete
                $('button#deleteAllBtn').addClass('d-none');
            }
        }
        // update all
        $(document).on('click', 'button#deleteAllBtn', function() {
            var checkedCountries = [];
            $('input[name="country_checkbox"]:checked').each(function() {
                checkedCountries.push($(this).data('id'));
            });

            var url = '{{ url("admin/overtimes/updates") }}';
            if (checkedCountries.length > 0) {
                console.log('testing');
                $.post(url, {
                            countries_ids: checkedCountries
                        }, function(data) {
                            console.log(data);
                            if (data.code == 0) {
                                $('#user-table').DataTable().draw(false);
                                $('#user-table').DataTable().on('draw', function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });

                                swal({
                                    title: "Success!",
                                    text: "Data has been update successfully!",
                                    icon: "success",
                                    timer: 3000
                                });
                            } else {
                                swal({
                                    title: "Sorry",
                                    text: data.message,
                                    icon: "info",
                                    timer: 3000
                                });
                            }
                        }, 'json');
            //     $.ajax({
            //     type: type,
            //     url: ajaxurl,
            //     data: formData,
            //     dataType: 'json',
            //     success: function(data) {
            //         console.log(state);
                   
            //     },
            //     error: function(data) {

            //         try {
                       
            //         } catch {
                        
            //         }
            //     }
            // });
                // swal({
                //     title: 'Are you sure?',
                //     html: 'You want to update <b>(' + checkedCountries.length + ')</b> countries',
                //     showCancelButton: true,
                //     showCloseButton: true,
                //     confirmButtonText: 'Yes, Update',
                //     cancelButtonText: 'Cancel',
                //     confirmButtonColor: '#556ee6',
                //     cancelButtonColor: '#d33',
                //     width: 300,
                //     allowOutsideClick: false
                // }).then(function(result) {
                //     console.log('sinit');
                //     if (result.value) {
                //         console.log('work or not');
                //         // pus url 
                        
                //     }
                // })
            }
        });
        // document.getElementById('btn-submit').onclick = function() {
        //     var markedCheckbox = document.querySelectorAll('input[type="checkbox"]:checked');
        //     for (var checkbox of markedCheckbox) {
        //        console.log(checkbox.value);
        //     }
        //     console.log(markedCheckbox.length);
        //     toggledeleteAllBtn();
        // }

        // function toggledeleteAllBtn(){
        //        if( $('input[type="checkbox"]:checked').length > 0 ){
        //            $('button#deleteAllBtn').text('Delete ('+$('input[type="checkbox"]:checked').length+')').removeClass('d-none');
        //        }else{
        //         // new classs create in button delete
        //            $('button#deleteAllBtn').addClass('d-none');
        //        }
        //    }

    });

    function checkCheckbox() {
        // var inputs = document.querySelectorAll('.checkIt');   
        // for (var i = 0; i < inputs.length; i++) {   
        //     inputs[i].checked = true;   
        //     console.log(inputs[i].value);
        // }   
        // work
        // var yes = document.getElementById("checkIt");
        // var no = document.getElementById("myCheck2");
        // if (yes.checked == true ) {
        //     console.log(yes.value)
        //     console.log('check');
        // } 
        // else if (yes.checked == true) {
        //     var y = document.getElementById("myCheck1").value;
        //     return document.getElementById("result").innerHTML = y;
        // } else if (no.checked == true) {
        //     var n = document.getElementById("myCheck2").value;
        //     return document.getElementById("result").innerHTML = n;

        // }
        //  else {
        //     console.log('error');
        // }

    }
</script>
@endsection