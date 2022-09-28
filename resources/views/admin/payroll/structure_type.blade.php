@extends('admin.layouts.master')
@section('title', 'Structure type')

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
                        <label for="nip">Name <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name..." autocomplete="off">
                        <div class="invalid-feedback" id="valid-name"></div>
                    </div>

                    <!-- <div class="form-group">
                        <label for="name">Types </label>
                        <input type="text" class="form-control" id="type" name="type" placeholder="Enter types..." autocomplete="off">
                        <div class="invalid-feedback" id="valid-type"></div>
                    </div> -->
                </form>

            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
            <h1>{{ __('item.structure_type') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    Structure type List
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
            ajax: "{{url('admin/structuretype')}}",
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
            $('.modal-title').html('Create Data');
            $('#company-form').trigger('reset');
            $('#btn-save').html('<i class="fas fa-check"></i> Save Data');
            $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
            $('#btn-save').val('save').removeAttr('disabled');
        });

        // Store new company or update company
        $('#btn-save').click(function() {
            // save data state
            var formData = {
                name: $('#name').val(),
                

            };

            var state = $('#btn-save').val();

            var type = "POST";
            var ajaxurl = "{{url('admin/structuretype/store')}}";
            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            console.log(state);
            // update state
            if (state == "update") {
                console.log(state);
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                var id = $('#id').val();
                type = "PUT";
                ajaxurl = "{{ url('admin/structuretype/update') }}" + '/' + id;
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
                            timer: 2000
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

                        $('#user-table').DataTable().draw(false);
                        $('#user-table').DataTable().on('draw', function() {
                            $('[data-toggle="tooltip"]').tooltip();
                        });
                    }

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
                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } else {

                            if (data.responseJSON.errors.name) {
                                $('#name').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-name').html(data.responseJSON.errors.name);
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

                        $('#formModal').modal('hide');
                    }
                }
            });
        });
        //  Edit Category
        $(document).on('click', '#editBtn', function() {
            var id = $(this).attr('data-id');
            console.log(id);
            $.get("{{ url('admin/structuretype/edit') }}" + '/' + id, function(data) {
                $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                // show data on modal
                // $('#name').val(data.nip);
                $('#id').val(data.id);
                $('#name').val(data.name);
               

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
                            url: "{{ url('admin/structuretype/delete') }}" + '/' + id,
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