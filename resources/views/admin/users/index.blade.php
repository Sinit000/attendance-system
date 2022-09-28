@extends('admin.layouts.master')
@section('title', 'Employee')
@section('head','Employee')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
<!-- Modal -->

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <!-- <h1>Employee Data</h1> -->
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <!-- <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a> -->
                </div>
                <div class="breadcrumb-item">
                    <!-- <i class="fas fa-user"></i>
                    Employee List -->
                </div>
            </div>
        </div>
        <!-- alert error -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {!! session('success') !!}
            </div>
        </div>
        @endif
        <div class="section-body">
            <div class="card card-primary">
                <div class="card-header">
                    <div class="ml-auto">
                        <a href="{{url('admin/user/create')}}" class="btn btn-primary "><i class="fas fa-plus-circle">Create Data</i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="user-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <!-- <th>NO</th> -->
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Position</th>
                                    <th>Department</th>
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
            ajax: "{{url('admin/user')}}",
            columns: [


                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'image',
                    name: 'image'

                },
                // {
                //     data: 'no',
                //     name: 'no'
                // },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'employee_phone',
                    name: 'employee_phone'
                },
                {
                    data: 'position.position_name',
                    name: 'position.position_name'
                },
                {
                    data: 'department.department_name',
                    name: 'department.department_name'
                },

                {
                    "data": null,
                    render: function(data, type, row) {

                        return `<div><a href="{{url('admin/user/details')}}/${row.id}"><button data-toggle="tooltip" data-original-title="View" data-id="${row.id}" class="btn btn-sm btn-icon btn-warning"  id="editBtn"><i class="fa fa-eye"></i></button></a>  <a href="{{url('admin/user/edit')}}/${row.id}"><button data-toggle="tooltip" data-original-title="Edit" data-id="${row.id}" class="btn btn-sm btn-icon btn-primary"  id="editBtn"><i class="fa fa-edit"></i></button></a>   <button data-toggle="tooltip" data-original-title="Delete" data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
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
            $('#btn-save').html('<i class="fas fa-check"></i> Simpan');
            $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
            $('#btn-save').val('save').removeAttr('disabled');
        });

        // Store new company or update company

        //  Edit Category



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
                            url: "{{ url('admin/user/delete') }}" + '/' + id,
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
                                    title: "Success!",
                                    text: "Data has been deleted successfully!",
                                    icon: "success",
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