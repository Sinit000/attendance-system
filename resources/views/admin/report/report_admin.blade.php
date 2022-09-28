@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('head','Report')
@section('css')
<link rel="stylesheet" href="https://demo.getstisla.com/assets/modules/fullcalendar/fullcalendar.min.css">

@endsection

@section('content')
<!-- Main Content -->
<div class="main-content">
    <section class="section ">
        <div class="section-header pt-80 border-0" style="background-color: transparent;">
            <!-- <h1>Dashboard</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
            </div> -->
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div class="card">
                    <div class="card-header">
                        <h4>Welcome to Attendance App</h4>
                    </div>

                    </div> -->
                    <div class="row" >
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <a href="reports/employee"><h4>Employee Report</h4></a>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <a href="reports/attendances"><h4>Attendance Report</h4></a>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <a href="reports/overtimes"><h4>Overtime Report</h4></a>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <a href="report/leaves"><h4>Leave Report</h4></a>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Absent</h4>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="far fa-newspaper"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Leave</h4>
                                    </div>
                                    <div class="card-body">
                                       
                                    </div>
                                </div>
                            </div>
                        </div> -->
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
            ajax: "{{url('admin/attendances')}}",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user.name',
                    name: 'name'
                },
                // {
                //     "data":null,
                //     render: function(data,type,row){
                //         return `<div>  <button data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn">${type.name}</button>   </div>`
                //     }
                // },
                {
                    data: 'checkin_time',
                    name: 'checkin_time'
                },
                {
                    data: 'checkout_time',
                    name: 'checkout_time'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                // {
                //     data: 'date',
                //     name: 'date'
                // },

            ],
        });

        $('#user-table').DataTable().on('draw', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });



        // Store new company or update company

    });
</script>
@endsection