@extends('admin.layouts.master')
@section('title', 'Checkin')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
<!-- Modal -->


   <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Absent Data</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-user"></i>
                       Absent List
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
                            <table class="table table-sm table-hover table-bordered" id="user-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Employee Name </th>
                                        <!-- <th>Time in</th> -->
                                        <!-- <th>Checkin time </th> -->
                                        <th>Status</th>
                                        <!-- <th>Late </th>
                                        <th>Date </th> -->
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
                ajax: "{{url('admin/absent')}}",

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
                        data: 'status',
                        name: 'status'
                    },


                ],
            });

            $('#user-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });



            // Store new company or update company

        });
    </script>
@endsection
