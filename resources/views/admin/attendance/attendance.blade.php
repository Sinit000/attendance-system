@extends('admin.layouts.master')
@section('title', 'Attendance')

@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
<!-- Modal -->


<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Attendance</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    Attendance List
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
                                    <th>Checkin time </th>
                                    <th>Checkin Status </th>
                                    <th>Late</th>
                                    <th>Checkout time </th>
                                    <th>Checkout Status</th>
                                    <th>Early</th>
                                    <th>Status</th>
                                    <th>Date </th>
                                    <th>Duration</th>
                                    <th>Action </th>
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
            ajax: "{{url('admin/attendances')}}",

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
                    data: 'checkin_status',
                    name: 'checkin_status'
                },
                {
                    data:'checkin_late',
                    // data: 'checkin.checkin_late',
                    name: 'checkin_late'
                },
                {
                    data: 'checkout_time',
                    name: 'checkout_time'
                },
                {
                    data: 'checkout_status',
                    name: 'checkout_status'
                },
                {
                    data: 'checkout_late',
                    name: 'checkout_late'
                },
                //    {
                //         "data": null,
                //         render: function(data, type, row) {
                //             return checkin.status=="checkin"? `<div>  <button data-toggle="tooltip" data-original-title="Edit" data-id="${row.id}" class="btn btn-success btn-round float-right"  id="editBtn">Checkin</button>   </div>`:`<div>  <button data-toggle="tooltip" data-original-title="Edit" data-id="${row.id}" class="btn btn-success btn-round float-right"  id="editBtn">Sinit</button>   </div>`
                //         }
                //     },


                {
                    data: 'btn',
                    name: 'btn'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data:'duration',
                    name:'duration',
                },
                {
                    data: 'action',
                    name: 'action'
                }
                // {
                //     "data": null,
                //     render: function(data, type, row) {
                //         return `<div>  <button data-toggle="tooltip" data-original-title="Edit" data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-toggle="tooltip" data-original-title="Delete" data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                //     }
                // },

            ],
        });

        $('#user-table').DataTable().on('draw', function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // checkin
        $(document).on('click', '#checkin', function() {
            // console.log('hisinit');
            // show loading 
            $('#checkin').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            // $('#checkin').remove();
            var userId = $(this).attr('data-id');

            console.log(userId)
            // remove loading

            $.ajax({
                type: "POST",
                url: "{{url('admin/attendances/checkin')}}" + '/' + userId,
                data: '',
                dataType: 'json',
                success: function(data) {
                    console.log('save sinit');
                    console.log(data);
                    if (data.code == 0) {
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
                        swal({
                            title: "Error!",
                            text: data.message,
                            icon: "error",
                            timer: 3000
                        });
                    }
                    $('#checkin').html('Checkin');
                    $('#checkin').removeAttr('disabled');
                    console.log('success');


                },
                error: function(data) {
                    try {
                        swal({
                            title: "Sorry!",
                            text: "An error occurred, please try again",
                            icon: "error",
                            timer: 3000
                        });
                    } catch {
                        swal({
                            title: "Sorry!",
                            text: "An error occurred, please try again",
                            icon: "error",
                            timer: 3000
                        });
                    }
                }
            });

        });
        // checkout
        $(document).on('click', '#checkout', function() {
            // console.log('hisinit');
            // show loading 
            $('#checkout').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
            // $('#checkin').remove();
            var checkinId = $(this).attr('data-id');

            console.log(checkinId)
            // remove loading

            $.ajax({
                type: "PUT",
                url: "{{url('admin/attendances/checkout')}}" + '/' + checkinId,
                data: '',
                dataType: 'json',
                success: function(data) {
                    console.log('save sinit');
                    console.log(data);
                    if (data.code == 0) {
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
                        swal({
                            title: "Error!",
                            text: data.message,
                            icon: "error",
                            timer: 3000
                        });
                    }
                    $('#checkout').html('Checkout');
                    $('#checkout').removeAttr('disabled');
                    console.log('success');


                },
                error: function(data) {
                    try {
                        swal({
                            title: "Sorry!",
                            text: "An error occurred, please try again",
                            icon: "error",
                            timer: 3000
                        });
                        $('#checkout').html('Checkout');
                        $('#checkout').removeAttr('disabled');
                    } catch {
                        swal({
                            title: "Sorry!",
                            text: "An error occurred, please try again",
                            icon: "error",
                            timer: 3000
                        });
                        $('#checkout').html('Checkout');
                        $('#checkout').removeAttr('disabled');
                    }
                }
            });

        });

        // Store new company or update company

    });
</script>
@endsection