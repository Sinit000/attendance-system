@extends('admin.layouts.master')
@section('title', 'Payroll')
@section('head','Payslip Employee')

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
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="form-group">
                        <label for="nip">Deduction </label>
                        <input type="text" class="form-control" id="deduction" name="deduction" placeholder="Enter deduction..." autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="nip">Bonus </label>
                        <input type="text" class="form-control" id="bonus" name="bonus" placeholder="Enter bonus..." autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="nip">Advance Money </label>
                        <input type="text" class="form-control" id="advance_salary" name="advance_salary" placeholder="Enter advance_salary..." autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="nip">Notes </label>
                        <input type="text" class="form-control" id="notes" name="notes" placeholder="Enter advance_salary..." autocomplete="off">
                    </div>

                </form>

            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-close">
                    <i class="fas fa-times"></i>
                    Close
                </button>
                <button type="button" id="btn-save" class="btn btn-primary">
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
            <!-- <h1>Payroll Data</h1> -->
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <!-- <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a> -->
                </div>
                <div class="breadcrumb-item">
                    <!-- <i class="fas fa-user"></i>
                    payroll List -->
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
            <div class="card card-info">
                <div class="card-header">
                    <div class="ml-auto">
                        <a href="{{url('admin/payslip/create')}}" class="btn btn-info "><i class="fas fa-plus-circle">Create Data</i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="user-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>W-Hour</th>
                                    <th>Basic Salary</th>
                                    <th>Allowance</th>
                                    <th>Advance Salary</th>

                                    <th>Net Per day</th>
                                    <th>Net Per hour</th>
                                    <th>Standard Attendance</th>
                                    <th>Total Attendance</th>
                                    <th>Gross Salary</th>
                                    <!-- <th>Senority Salary</th> -->
                                    <!-- <th>Advance Salary</th> -->
                                    <!-- <th>Bonus</th> -->
                                    <!-- <th>OT Hour</th> -->
                                    <!-- <th>Total OT</th> -->

                                    <!-- <th>Total Leave</th>
                                    <th>Allowance</th> -->


                                    <th>Salary Tax</th>
                                    <th>Allowance Tax</th>
                                    <!-- <th>OT Rate</th>
                                    <th>OT Hour</th>
                                    <th>OT Method</th> -->

                                    <!-- <th>Gross Pay</th> -->
                                    <th>Deduction</th>
                                    <th>Net Salary</th>
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
            searchable: true,
            // orderable:false,
            ajax: "{{url('admin/payslip')}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'from_date',
                    name: 'from_date'

                },
                {
                    data: 'to_date',
                    name: 'to_date'

                },
                {
                    data: 'user_name',
                    name: 'user_name'

                },
                {
                    data: 'position',
                    name: 'position'

                },
                {
                    data: 'wage_hour',
                    name: 'wage_hour'
                },

                {
                    data: 'base_salary',
                    name: 'base_salary'
                },
                {
                    data: 'allowance',
                    name: 'allowance'
                },
                {
                    data: 'advance_salary',
                    name: 'advance_salary'
                },



                {
                    data: 'net_perday',
                    name: 'net_perday'
                },
                {
                    data: 'net_perhour',
                    name: 'net_perhour'
                },
                {
                    data: 'standance_attendance',
                    name: 'standance_attendance'
                },
                {
                    data: 'total_attendance',
                    name: 'total_attendance'
                },
                // {  
                //     data: 'total_leave',
                //     name: 'total_leave'
                // },



                // {
                //     data: 'allowance',
                //     name: 'allowance'
                // },
                // {
                //     data: 'tax_allowance',
                //     name: 'tax_allowance'
                // },
                // {
                //     data: 'ot_rate',
                //     name: 'ot_rate'
                // },
                // {
                //     data: 'ot_hour',
                //     name: 'ot_hour'
                // },
                // {
                //     data: 'ot_method',
                //     name: 'ot_method'
                // },
                {
                    data: 'gross_salary',
                    name: 'gross_salary'
                },

                {
                    data: 'tax_salary',
                    name: 'tax_salary'
                },
                {
                    data: 'tax_allowance',
                    name: 'tax_allowance'
                },

                {
                    data: 'deduction',
                    name: 'deduction'
                },


                {
                    data: 'net_salary',
                    name: 'net_salary'
                },

                {
                    "data": null,
                    render: function(data, type, row) {

                        return `<div>  <button data-toggle="tooltip" data-original-title="Edit" data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-toggle="tooltip" data-original-title="Delete" data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                    }
                },
            ],
        });

        $('#user-table').DataTable().on('draw', function() {
            $('[data-toggle="tooltip"]').tooltip();
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
                            url: "{{ url('admin/payslip/delete') }}" + '/' + id,
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

        $(document).on('click', '#editBtn', function() {
            var id = $(this).attr('data-id');


            $.get("{{ url('admin/payslip/edit') }}" + '/' + id, function(data) {
                $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                // show data on modal
                // $('#name').val(data.nip);
                $('#id').val(data.id);
                $('#user_id').val(data.user_id);
                $('#deduction').val(data.deduction);
                $('#bonus').val(data.bonus);
                $('#advance_salary').val(data.advance_salary);

                $('#notes').val(data.notes);
                // change value button save to update then state to save
                $('#btn-save').val('update').removeAttr('disabled');
                $('#formModal').modal('show');
                $('.modal-title').html('Edit Data');
                $('#null').html('<small id="null">Kosongkan jika tidak ingin di ubah</small>');
                $('#btn-save').html('<i class="fas fa-check"></i> Edit');
            }).fail(function() {
                swal({
                    title: "Sorry!",
                    text: "Faild to update data!",
                    icon: "error",
                    timer: 3000
                });
            });

            $('#formModal').modal('show');
            $('.modal-title').html('Edit Data');
            $('.close').click(function() {
                $('#id').val('');
                $('#user_id').val('');
                $('#deduction').val('');
                $('#bonus').val('');
                $('#advance_salary').val('');
                $('#notes').val('');
            })
            $('#btn-close').click(function() {
                $('#id').val('');
                $('#user_id').val('');
                $('#deduction').val('');
                $('#bonus').val('');
                $('#advance_salary').val('');
                $('#notes').val('');
            })
        });

        $('#btn-save').click(function() {



            var id = $('#id').val();
            var formData = {
                deduction: $('#deduction').val(),
                user_id: $('#user_id').val(),
                bonus: $('#bonus').val(),
                advance_salary: $('#advance_salary').val(),
                notes: $('#notes').val(),
            };

            $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            $.ajax({
                type: "PUT",
                url: "{{url('admin/payslip/update')}}" + '/' + id,
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    // console.log('before send'); // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    // $('#loader').removeClass('hidden')
                },
                success: function(data) {
                    console.log(data);
                    if (data.code == 0) {
                        swal({
                            title: "Success!",
                            text: "Data has been added successfully!",
                            icon: "success",
                            timer: 2000
                        });
                    } else {
                        swal({
                            title: "Sorry!",
                            text: data.message,
                            icon: "error",
                            timer: 3000
                        });
                    }


                    $('#user-table').DataTable().draw(false);
                    $('#user-table').DataTable().on('draw', function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    });

                    $('#formModal').modal('hide');
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

                        $('#formModal').modal('hide');
                    }
                },
                complete: function() { // Set our complete callback, adding the .hidden class and hiding the spinner.
                    // $('#loader').addClass('hidden')
                },
            });



        });





    });
</script>

@endsection