@extends('admin.layouts.master')
@section('title', 'Structure')

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
                                <label for="">Name <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name..." autocomplete="off">
                                <div class="invalid-feedback" id="valid-name"></div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Structure type <sup class="text-danger">*</sup></label>
                                <select class="form-control select2" id="structure_type_id" name="structure_type_id" style="width: 100%;">
                                    <option value="">Choose Type</option>

                                </select>
                                <div class="invalid-feedback" id="valid-structure_type_id"></div>
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="form-group">
                        <label for="gender">Currency <sup class="text-danger">*</sup></label>
                        <select class="form-control " id="currency" name="currency" style="width: 100%;">
                            <option value="">Choose Currency</option>
                            <option value="riel">Riel</option>
                            <option value="usd">USD</option>

                        </select>
                        <div class="invalid-feedback" id="valid-currency"></div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Base salary <sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="base_salary" name="base_salary" placeholder="Enter base_salary..." autocomplete="off">
                                <div class="invalid-feedback" id="valid-base_salary"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">allowance </label>
                                <input type="text" class="form-control" id="allowance" name="allowance" placeholder="Enter allowance..." autocomplete="off">
                                <!-- <div class="invalid-feedback" id="valid-base_salary"></div> -->
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Bonus </label>
                                <input type="text" class="form-control" id="bonus" name="bonus" placeholder="Enter bonus..." autocomplete="off">
                               
                            </div>
                        </div> -->
                    </div>

                    <!-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">allowance </label>
                                <input type="text" class="form-control" id="allowance" name="allowance" placeholder="Enter allowance..." autocomplete="off">
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Senority salary </label>
                                <input type="text" class="form-control" id="senority_salary" name="senority_salary" placeholder="Enter senority_salary..." autocomplete="off">
                                
                            </div>
                        </div>

                    </div> -->

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
            <h1>Structure</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    Structure
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
                                    <th>Name </th>
                                    <!-- <th>Structure type </th> -->
                                    <th>Base Salary </th>
                                    <!-- <th>Bonus</th> -->
                                    <th>Allowance</th>
                                    <!-- <th>Seniority Salary </th> -->

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
            ajax: "{{url('admin/structure')}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                // {
                //     data: 'type.name',
                //     name: 'type.name'
                // },
                {
                    data: 'base_salary',
                    name: 'base_salary'
                },
                // {
                //     data: 'bonus',
                //     name: 'bonus'
                // },
                {
                    data: 'allowance',
                    name: 'allowance'
                },
                // {
                //     data: 'senority_salary',
                //     name: 'senority_salary'
                // },

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
            // $.ajax({
            //     type: "GET",
            //     url: "{{url('admin/structure/componet')}}",
            //     // data:"",
            //     dataType: "json",
            //     success: function(response) {
            //         console.log(response.data);
            //         console.log(response);
            //         if (response.status == 404) {


            //         } else {

            //             console.log(response.data)
            //             var selOpts = "";
            //             for (i = 0; i < response.data.length; i++) {
            //                 console.log(response.data[i]['name']);
            //                 var id = response.data[i]['id'];
            //                 var val = response.data[i]['name'];
            //                 selOpts += "<option value='" + id + "'>" + val + "</option>";

            //             }

            //             $('#structure_type_id').append(selOpts);

            //         }
            //     }
            // });
            $('.modal-title').html('Create Data');
            $('#company-form').trigger('reset');
            $('#btn-save').html('<i class="fas fa-check"></i> Save Data');

            $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
            $('#btn-save').val('save').removeAttr('disabled');
            // if click close button
            // $('.close').click(function() {
            //     // remove that from select value after save data to avoid dublicate data
            //     console.log('close button');
            //     $('#structure_type_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');

            // })
            // $('#btn-close').click(function() {
            //     // remove that from select value after save data to avoid dublicate data
            //     console.log('close button');
            //     $('#structure_type_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');

            // })
        });


        // Store new company or update company
        $('#btn-save').click(function() {
            // save data state
            var formData = {
                name: $('#name').val(),
                // structure_type_id: $('#structure_type_id').val(),
                base_salary: $('#base_salary').val(),
                // currency: $('#currency').val(),
                allowance: $('#allowance').val(),
                // senority_salary: $('#senority_salary').val(),
            };

            var state = $('#btn-save').val();


            var type = "POST";
            var ajaxurl = "{{url('admin/structure/store')}}";
            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            console.log(state);
            console.log(ajaxurl);
            // update state
            if (state == "update") {
                console.log(state);
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                var id = $('#id').val();
                type = "PUT";
                ajaxurl = "{{ url('admin/structure/update') }}" + '/' + id;
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
                    // $('#structure_type_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');

                    $('#formModal').modal('hide');
                },
                error: function(data) {

                    try {
                        if (state == "save") {

                            if (data.responseJSON.errors.name) {
                                $('#name').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-name').html(data.responseJSON.errors.name);
                            }
                            // if (data.responseJSON.errors.structure_type_id) {
                            //     $('#structure_type_id').removeClass('is-valid').addClass('is-invalid');
                            //     $('#valid-structure_type_id').removeClass('valid-feedback').addClass('invalid-feedback');
                            //     $('#valid-structure_type_id').html(data.responseJSON.errors.structure_type_id);
                            // }
                            if (data.responseJSON.errors.base_salary) {
                                $('#base_salary').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-base_salary').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-base_salary').html(data.responseJSON.errors.base_salary);
                            }
                            // if (data.responseJSON.errors.currency) {
                            //     $('#currency').removeClass('is-valid').addClass('is-invalid');
                            //     $('#valid-currency').removeClass('valid-feedback').addClass('invalid-feedback');
                            //     $('#valid-currency').html(data.responseJSON.errors.currency);
                            // }


                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } else {
                            if (data.responseJSON.errors.name) {
                                $('#name').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-name').html(data.responseJSON.errors.name);
                            }
                            // if (data.responseJSON.errors.structure_type_id) {
                            //     $('#structure_type_id').removeClass('is-valid').addClass('is-invalid');
                            //     $('#valid-structure_type_id').removeClass('valid-feedback').addClass('invalid-feedback');
                            //     $('#valid-structure_type_id').html(data.responseJSON.errors.structure_type_id);
                            // }
                            if (data.responseJSON.errors.base_salary) {
                                $('#base_salary').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-base_salary').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-base_salary').html(data.responseJSON.errors.base_salary);
                            }
                            // if (data.responseJSON.errors.currency) {
                            //     $('#currency').removeClass('is-valid').addClass('is-invalid');
                            //     $('#valid-currency').removeClass('valid-feedback').addClass('invalid-feedback');
                            //     $('#valid-currency').html(data.responseJSON.errors.currency);
                            // }


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
            $.get("{{ url('admin/structure/edit') }}" + '/' + id, function(data) {
                $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                // show data on modal
                // $('#name').val(data.nip);
                $('#id').val(data.data.id);
                $('#base_salary').val(data.data.base_salary);
                // $('#currency').val(data.data.currency);
                $('#allowance').val(data.data.allowance);
                // $('#senority_salary').val(data.data.senority_salary);
                $('#name').val(data.data.name);

                // $('#structure_type_id').val(data.data.structure_type_id);



                // var selOpts = "";
                // $.each(data.type, function(key, value) {
                //     // selOpts=  `<option value="${value.id}" ${value.id == response.data['workday_id'] ? 'selected' : ''}>${value.name}</option>`;
                //     selOpts = $('select[name="structure_type_id"]').append(`<option value="${value.id}" ${value.id == data.data.structure_type_id? 'selected' : ''}>${value.name}</option>`)
                // });
                // // $('.editModal').append(selOpts);
                // $('#formModal').find('#structure_type_id').append(selOpts)
                // //                       // timetable
                // var time = "";




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
                            url: "{{ url('admin/structure/delete') }}" + '/' + id,
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