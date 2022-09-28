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
            ajax: "{{url('admin/report/accountant/employees')}}",
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

                        return `<div><a href="{{url('admin/user/details')}}/${row.id}"><button data-toggle="tooltip" data-original-title="View" data-id="${row.id}" class="btn btn-sm btn-icon btn-warning"  id="editBtn"><i class="fa fa-eye"></i></button></a></div>`
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

       
    });
</script>
@endsection