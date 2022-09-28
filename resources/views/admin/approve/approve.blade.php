@extends('admin.layouts.master')
@section('title', 'Approve')

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
                        <label for="status">Status <sup class="text-danger">*</sup></label>
                        <select class="form-control select2" id="status" name="status" style="width: 100%;">
                            <!-- <option value="">Choose status</option> -->
                            <option value="approve">Approve</option>
                            <option value="reject">Reject</option>
                        </select>
                        <div class="invalid-feedback" id="valid-day"></div>
                    </div>
                    <div class="form-group">
                        <label for="leave_deduction">Leave Deduction <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="leave_deduction" name="leave_deduction"
                            placeholder="Enter notes..." autocomplete="off">0
                        <div class="invalid-feedback" id="valid-leave_deduction"></div>
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

   <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Approval Leave</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ url('admin/dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-user"></i>
                       Approve List
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
                                        <th>Reason </th>
                                        <th>From Date </th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Request Date </th>
                                        <th>Leave Deduction</th>
                                        <th>Status </th>
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
                ajax: "{{url('admin/approve/leaves')}}",
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
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'leave_deduction',
                        name: 'leave_deduction'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    // status=="pending"? {
                    //     "data": null,
                    //     render: function(data, type, row) {
                    //         return `<div>  <button data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                    //     }
                    // }:
                    {"data": null,
                        render: function(data, type, row) {
                        return  data.status=="pending"? `<div>  <button data-id="${row.id}" class="btn btn-sm btn-icon btn-info"  id="editBtn"><i class="fa fa-edit"></i></button>   <button data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`:   `<div>  <button data-id="${row.id}"  class="btn btn-sm btn-icon btn-danger" data-original-title="Delete"  id="deleteBtn"><i class="fa fa-trash-alt"></i></button></div>`
                    }},
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



            // Store new company or update company
            $('#btn-save').click(function() {
                // save data state
                var formData = {
                    status: $('#status').val(),
                    leave_deduction: $('#leave_deduction').val(),

                };
                console.log(formData.status)

                var state = $('#btn-save').val();
            
                if (state == "update") {
                    console.log(state);
                    $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                    var id = $('#id').val();
                    type = "PUT";
                     ajaxurl = "{{ url('admin/approve/leaves/update') }}" + '/' + id;
                    console.log(ajaxurl);
                    console.log(id);
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
                            console.log("Sinit");
                            if(data.code ==0){
                                swal({
                                    title: "Success!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 3000
                                });
                            }else{
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

                        }
                        // remove that from select value after save data to avoid dublicate data
                        // $('#user_id').find('option').remove().end().append('<option value="">Chooose employee</option>').val('');
                        // $('#timetable_id').find('option').remove().end().append('<option value="">Chooose time</option>').val('');
                        $('#formModal').modal('hide');
                    },
                    error: function(data) {

                        try {
                            if (state == "save") {

                                if (data.responseJSON.errors.status) {
                                    $('#user_id').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-day').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-day').html(data.responseJSON.errors.status);
                                }
                                if (data.responseJSON.errors.leave_deduction) {
                                    $('#leave_deduction').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-leave_deduction').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-leave_deduction').html(data.responseJSON.errors.leave_deduction);
                                }



                                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                                $('#btn-save').removeAttr('disabled');
                            } else {
                                if (data.responseJSON.errors.status) {
                                    $('#user_id').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-day').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-day').html(data.responseJSON.errors.status);
                                }
                                if (data.responseJSON.errors.leave_deduction) {
                                    $('#leave_deduction').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-leave_deduction').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-leave_deduction').html(data.responseJSON.errors.leave_deduction);
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
                var id= $(this).attr('data-id');
                console.log(id);
                $.get("{{ url('admin/approve/leaves/edit') }}" + '/' + id , function(data) {
                    $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                    // show data on modal
                    // $('#name').val(data.nip);
                    $('#id').val(data.id);
                    $('#status').val(data.status);
                    console.log(data.status);
                    console.log(data.id);
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
            // $(document).on('click', '#deleteBtn', function(){
            //     var id= $(this).attr('data-id');
            //     console.log(id);
            //     swal("Warning!", "Are you sure you want to delete?", "warning", {
            //         buttons: {
            //             cancel: "NO!",
            //             ok: {
            //                 text: "Yes!",
            //                 value: "ok"
            //             }
            //         },
            //     }).then((value) => {
            //         switch (value) {
            //             case "ok" :
            //                 $.ajax({
            //                     type: "DELETE",
            //                     url: "{{ url('admin/schedule/delete') }}" + '/' + id,
            //                     success: function(data) {
            //                         if(data.code == 0){
            //                             $('#user-table').DataTable().draw(false);
            //                             $('#user-table').DataTable().on('draw', function() {
            //                                 $('[data-toggle="tooltip"]').tooltip();
            //                             });

            //                             swal({
            //                                 title: "Success!",
            //                                 text: "Data has been deleted successfully!",
            //                                 icon: "success",
            //                                 timer: 3000
            //                             });
            //                         }else{
            //                             swal({
            //                             title: "Sorry!",
            //                             text: data.message,
            //                             icon: "error",
            //                             timer: 3000
            //                         });
            //                         }

            //                     },
            //                     error: function(data) {
            //                         swal({
            //                             title: "Sorry!",
            //                             text: "An error occurred, please try again",
            //                             icon: "error",
            //                             timer: 3000
            //                         });
            //                     }
            //                 });
            //             break;

            //             default :
            //                 swal({
            //                     title: "Oh Ya!",
            //                     text: "Data is not change",
            //                     icon: "info",
            //                     timer: 3000
            //                 });
            //             break;
            //         }
            //     });
            // });
        });
    </script>
@endsection
