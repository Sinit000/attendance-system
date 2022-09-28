@extends('admin.layouts.master')
@section('title', 'Report')
@section('head','Report Attendance')

@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.5/datepicker.min.css" integrity="sha512-OuupWckAJJIcRPiQcajus5jyV6v8skGpZ+9LUETpclmq5a2eph8nspQO0u9an5firIwX6SF2jOV3YgoHWIO7+Q==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/css/bootstrap-datepicker.standalone.min.css" integrity="sha512-ZgpiugtcWdV2LX1a1uy6ckVtJ8J3W7VBgYpKzyqmJ0UFJef1biYdOlOM2hl35gkf9ki56WoUeDQlIRqgdUhKOQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    #user-table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #user-table td,
    #user-table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #user-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #user-table tr:hover {
        background-color: #ddd;
    }

    #user-table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #34ace0;
        color: white;
    }

    /*Hidden class for adding and removing*/
    .lds-dual-ring.hidden {
        display: none;
    }

    .overlay {
        /* position: fixed; */
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        /* background: grey; */
        /* background: rgba(0,0,0,.8); */
        z-index: 999;
        opacity: 1;
        transition: all 0.5s;
    }

    /*Spinner Styles*/
    .lds-dual-ring {
        display: inline-block;
        width: 80px;
        height: 80px;
        left: 50%;
        top: 80%;
        /* bottom: 50%; */
        margin-left: -4em;

    }

    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 5% auto;
        border-radius: 50%;
        border: 6px solid #000000;
        border-color: #000000 transparent #000000 transparent;
        animation: lds-dual-ring 1.2s linear infinite;

    }

    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
@endsection
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
@section('content')
<!-- Modal -->
@php
$fildter_data= [
'item'=> ['this week',1],
'item'=> ['this month',2],
'item'=> ['this year',3],

]
@endphp

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <!-- <h1>Attendance Report</h1> -->
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <!-- <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a> -->
                </div>
                <div class="breadcrumb-item">
                    <!-- <i class="fas fa-user"></i>
                    attendance List -->
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card card-info">
                <div class="card-header">
                    <div class="ml-auto">
                        <!-- <a href="{{url('admin/reports')}}" class="btn btn-info ">Back</a> -->
                        <div id="loader" class="lds-dual-ring hidden overlay"></div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="" class="form-control  select2" id="filter" style="width: 100%;">
                                    <option value="">Choose Value </option>
                                    <!-- <option value="1">Today</option> -->
                                    <!-- <option value="2">This week</option> -->
                                    <option value="3">This month</option>
                                    <option value="5">Last month</option>
                                    <!-- <option value="4">This year</option> -->
                                </select>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input  type="date" class="form-control" id="startDate" name="demoDate" autocomplete="off" placeholder="Start Date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="date" class="form-control" id="endDate" name="demoDate" placeholder="End Date" autocomplete="off">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <button class="btn btn-small btn-dark pull-right" style="margin-right:20px;margin-left:20px;" id="btn_export" type="">Export to Excel</button>
                        <button class="btn btn-small btn-info pull-right" id="btn_print" type="">Print</button>

                    </div>

                    <br>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="user-table">
                            <thead class="thead-light">
                                <tr>

                                    <th width="70" class="text-center">No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Position</th>
                                    <th class="text-center">Total Attendance</th>
                                    <th class="text-center">Total Deduction</th>
                                    <th>Action</th>


                                </tr>
                            </thead>

                        </table>
                    </div>

                    <!--  -->
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
<script type="text/javascript" src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/printThis.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var statDate = "";
        var endDate = "";
        var dataList = [];
        var idUser = "";
        var total_attendance = 0;



        // $('#startDate').datepicker({
        //     format: "dd-mm-yyyy",
        //     autoclose: true,
        //     todayHighlight: true
        // });



        // get data
        $('#startDate').change(function() {
            statDate = $('#startDate').val();
            
            

        });

        $('#endDate').change(function() {
            endDate = $('#endDate').val();

            $('#user-table tbody').remove();
            getData(statDate, endDate);

        });
        $('select').change(function() {
            var s = $('#filter').find("option:selected");
            var item = s.val();
            $('#user-table tbody').remove();
            if (item == "1") {
                console.log();
                
                getData('1', '1');

            }
            if (item == "2") {
                getData('2', '2');
            }
            if (item == "3") {
                getData('3', '3');
            }
            if (item == "4") {
                getData('4', '4');
            }
            if (item == "5") {
                getData('5', '5');
            }

        });



        function getData(startDate, endDate) {
            console.log('get data');
            $.ajax({
                type: "GET",
                url: "{{ url('admin/report/accountant/attendances/get') }}" + '/' + startDate + '/' + endDate,
                dataType: 'json',
                beforeSend: function() {
                    console.log('before send'); // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $('#loader').removeClass('hidden')
                },
                success: function(data) {
                    // ajax response return data , so must data.data to get the value


                    data.data.forEach((item, index) => {
                        if(item.new_attendance==0){
                            var element = `<tbody>
                                <tr>
                                    <td  class="text-center">${index+1}</td>
                                    <td  class="text-center">${item.user_name}</td>
                                    <td class="text-center">${item.position_name}</td>
                                    <td class="text-center">${item.total_checkin}</td>
                                    <td  class="text-center">${item.leave_deduction}</td>
                                    <td><button type="button" class="btn btn-success" data-one="${item.total_checkin}" data-two="${item.leave_deduction}" id="confirmBtn" data-id="${item.user_id}">Confirm</button> <button type="button" class="btn btn-info"  id="editBtn" data-one="${item.total_checkin}" data-two="${item.leave_deduction}" data-id="${item.user_id}">Edit</button></td> 
                                </tr>
                                </tbody>
                            `;
                        }else{
                            var element = `<tbody>
                                <tr>
                                    <td  class="text-center">${index+1}</td>
                                    <td  class="text-center">${item.user_name}</td>
                                    <td class="text-center">${item.position_name}</td>
                                    <td class="text-center">${item.new_attendance}</td>
                                    <td  class="text-center">${item.leave_deduction}</td>
                                    <td><button type="button" class="btn btn-success" data-one="${item.new_attendance}" data-two="${item.leave_deduction}" id="confirmBtn" data-id="${item.user_id}">Confirm</button> <button type="button" class="btn btn-info"  id="editBtn" data-one="${item.new_attendance}" data-id="${item.user_id}" data-two="${item.leave_deduction}">Edit</button></td> 
                                </tr>
                                </tbody>
                            `;
                        }
                        


                        $('#user-table').append(element);
                    });
                },
                complete: function() { // Set our complete callback, adding the .hidden class and hiding the spinner.
                    $('#loader').addClass('hidden')
                },
            });

        }
        $('#btn_print').click(function() {
            $('#user-table').printThis({
                importStyle: true,
                importCSS: true
            });
        });
        $('#btn_export').on('click', function() {
            console.log('send excel');
            // $serializeArray = serialize($array)
            console.log(dataList);
            var url = "{{URL::to('admin/report/export')}}" + '/' + dataList
            console.log(url);

            window.location.assign(url)
        });

    });
    $('input[name="country_checkbox"]').each(function() {
        this.checked = false;
    });
    $('input[name="main_checkbox"]').prop('checked', false);
    $('button#deleteAllBtn').addClass('d-none');
    // 
    $(document).on('click', 'input[name="main_checkbox"]', function() {
        if (this.checked) {
            $('input[name="country_checkbox"]').each(function() {
                this.checked = true;
            });
        } else {
            $('input[name="country_checkbox"]').each(function() {
                this.checked = false;
            });
        }
        console.log('show btn delete all');
        toggledeleteAllBtn();
    });

    $(document).on('change', 'input[name="country_checkbox"]', function() {

        if ($('input[name="country_checkbox"]').length == $('input[name="country_checkbox"]:checked').length) {
            $('input[name="main_checkbox"]').prop('checked', true);
        } else {
            $('input[name="main_checkbox"]').prop('checked', false);
        }
        //    remove btn delete all
        console.log('remove btn remove');
        toggledeleteAllBtn();
    });

    //    if click on btn delete
    function toggledeleteAllBtn() {
        if ($('input[name="country_checkbox"]:checked').length > 0) {
            $('button#deleteAllBtn').text('Submit (' + $('input[name="country_checkbox"]:checked').length + ')').removeClass('d-none');
        } else {
            // new classs create in button delete
            $('button#deleteAllBtn').addClass('d-none');
        }
    }
    // update all
    $(document).on('click', 'button#deleteAllBtn', function() {
        var checkedCountries = [];
        $('input[name="country_checkbox"]:checked').each(function() {
            checkedCountries.push($(this).data('id'));
        });

        var url = '{{ url("admin/overtimes/updates") }}';
        if (checkedCountries.length > 0) {
            console.log('testing');
            // console.log(checkedCountries[1]);
            $.post(url, {
                countries_ids: checkedCountries
            }, function(data) {
                console.log(data);
                if (data.code == 0) {
                    // $('#user-table').DataTable().draw(false);
                    // $('#user-table').DataTable().on('draw', function() {
                    //     $('[data-toggle="tooltip"]').tooltip();
                    // });

                    swal({
                        title: "Success!",
                        text: "Data has been send  successfully!",
                        icon: "success",
                        timer: 3000
                    });
                } else {
                    swal({
                        title: "Sorry",
                        text: data.message,
                        icon: "info",
                        timer: 3000
                    });
                }
            }, 'json');

        }
    });

    // 
    $(document).on('click', '#confirmBtn', function() {
        idUser = $(this).attr('data-id');

        console.log(idUser);
        total = $(this).attr('data-one');
        leave = $(this).attr('data-two');
        var formatStart =$('#startDate').val();
        var formatEnd =$('#endDate').val();
        var formDateRange = $('#filter').val();
       
        
        if(formDateRange==''){
            
        }else{
            formatStart= formDateRange;
            formatEnd= formDateRange;
        }
        console.log(formatStart);
        console.log(formatEnd);
        console.log('total attendance');
        var formData = {
            checkin: total,
            leave_deduction: leave,
            startDate: formatStart,
            endDate: formatEnd,

        };
        
        $.ajax({
            type: "POST",
            url: "{{url('admin/payslip/store')}}" + '/' + idUser ,
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                console.log('before send'); // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#loader').removeClass('hidden')
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
                $('#loader').addClass('hidden')
            },
        });
    });
    var totalAttendance=0;
    var leaveDeduction=0;
    $(document).on('click', '#editBtn', function() {
        idUser = $(this).attr('data-id');
        totalAttendance = $(this).attr('data-one');
        leaveDeduction = $(this).attr('data-two');
        
        $('#formModal').modal('show');
        $('.modal-title').html('Edit Data');
    });
    // insert into payroll
    $('#btn-save').click(function() {
        
        console.log('total attendance');
        console.log(totalAttendance);
        console.log(leaveDeduction);
        var formatStart =$('#startDate').val();
        var formatEnd =$('#endDate').val();
        var formDateRange = $('#filter').val();
        console.log('total attendance');
        
        if(formDateRange==''){
            
        }else{
            formatStart= formDateRange;
            formatEnd= formDateRange;
        }
        // var deduct = $('#deduction').val();
        // var totalDeduction =  deduct + leaveDeduction;
        
        var formData = {
            deduction: $('#deduction').val(),
            bonus: $('#bonus').val(),
            advance_salary: $('#advance_salary').val(),
            notes: $('#notes').val(),
            checkin: totalAttendance,
            leave_deduction: leaveDeduction,
            startDate: formatStart,
            endDate:formatEnd


        };

        $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "{{url('admin/payslip/store')}}" + '/' + idUser ,
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
       

        // $.ajax({
        //     type: "POST",
        //     url: "{{url('admin/payslip/store')}}" + '/' + idUser + '/' + formatStart + '/' + formatEnd, 
        //     data: formData,
        //     dataType: 'json',
        //     success: function(data) {
        //         console.log(state);
        //         swal({
        //             title: "Success!",
        //             text: "Data has been added successfully!",
        //             icon: "success",
        //             timer: 2000
        //         });

        //         $('#user-table').DataTable().draw(false);
        //         $('#user-table').DataTable().on('draw', function() {
        //             $('[data-toggle="tooltip"]').tooltip();
        //         });

        //         $('#formModal').modal('hide');
        //     },
        //     error: function(data) {
        //         try {
        //             swal({
        //                 title: "Sorry!",
        //                 text: "An error occurred, please try again",
        //                 icon: "error",
        //                 timer: 3000
        //             });
        //         } catch {
        //             swal({
        //                 title: "Sorry!",
        //                 text: "An error occurred, please try again",
        //                 icon: "error",
        //                 timer: 3000
        //             });

        //             $('#formModal').modal('hide');
        //         }
        //     }
        // });
    });
    $('.close').click(function() {

        // console.log('close button');
        $('#company-form').trigger('reset');
    })
    $('#btn-close').click(function() {

        // remove that from select value after save data to avoid dublicate data
        $('#company-form').trigger('reset');
    })

    // 
</script>
@endsection