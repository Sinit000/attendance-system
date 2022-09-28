@extends('admin.layouts.master')
@section('title', 'Counter')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')


   <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Employee Counter</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ url('admin/dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-user"></i>
                       Counter List
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="card card-info">
                    <div class="card-header">
                        <!-- <button class="btn btn-primary ml-auto" id="btn-add">
                            <i class="fas fa-plus-circle"></i>
                            Create Data
                        </button> -->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="user-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Employee Name </th>
                                        <th>Position</th>
                                        <th>Total OT </th>
                                        <th>Total PH </th>
                                        <th>Hospitality Leave</th>
                                        <th>Marriage Leave</th>
                                        <th>Peternity Leave</th>
                                        <th>Funeral Leave</th>
                                        <th>Maternity Leave</th>
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
                ajax: "{{url('admin/counter')}}",
                columns: [
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
                        data: 'user.position.position_name',
                        name: 'user.position.position_name'
                    },
                    {
                        data: 'ot_duration',
                        name: 'ot_duration'
                    },
                    {
                        data: 'total_ph',
                        name: 'total_ph'
                    },
                    {
                        data: 'hospitality_leave',
                        name: 'hospitality_leave'
                    },
                    {
                        data: 'marriage_leave',
                        name: 'marriage_leave'
                    },
                    {
                        data: 'peternity_leave',
                        name: 'peternity_leave'
                    },
                    {
                        data: 'funeral_leave',
                        name: 'funeral_leave'
                    },
                    {
                        data: 'maternity_leave',
                        name: 'maternity_leave'
                    },
                    
                   
                ],
            });

            $('#user-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });



            
        });
    </script>
@endsection
