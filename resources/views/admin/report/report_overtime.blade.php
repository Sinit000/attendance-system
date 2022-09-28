@extends('admin.layouts.master')
@section('title', 'Report')
@section('head','Report Overtime')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
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
        background-color: #04AA6D;
        color: white;
    }
    /*Hidden class for adding and removing*/
    .lds-dual-ring.hidden {
        display: none;
    }

    /*Add an overlay to the entire page blocking any further presses to buttons or other elements.*/
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
            <!-- <h1>Overtime Report</h1> -->
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <!-- <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a> -->
                </div>
                <div class="breadcrumb-item ">
                    <!-- <i class="fas fa-user"></i>
                    overtime List -->
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card card-info">
                <div class="card-header">
                    <div class="ml-auto">
                        <a href="{{url('admin/reports')}}" class="btn btn-info ">Back</a>
                        <div id="loader" class="lds-dual-ring hidden overlay"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="" class="form-control  select2" id="user_id" style="width: 100%;">
                                    <option value="">Choose Empployee </option>
                                    @foreach($data as $r)
                                    <option value="{{$r->id}}">{{$r->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="" class="form-control  select2" id="filter" style="width: 100%;">
                                    <option value="">Choose Value </option>
                                    <option value="1">Today</option>
                                    <option value="2">This week</option>
                                    <option value="3">This month</option>
                                    <option value="5">Last month</option>
                                    <option value="4">This year</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="date" class="form-control" id="startDate" name="demoDate" autocomplete="off" placeholder="Start Date">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                                    <th><input type="checkbox" name="main_checkbox"><label></label></th>
                                    <th width="70" class="text-center">No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Position</th>
                                    <th class="text-center">Reason</th>
                                    <th class="text-center">From Date</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Duration</th>
                                    <th class="text-center">OT Rate</th>
                                    <th class="text-center">OT Hour</th>
                                    <th class="text-center">OT Method</th>
                                    <th class="text-center">Total OT</th>

                                    <th class="text-center">Request Date</th>
                                    <th class="text-center">Request By</th>
                                    <th class="text-center">Pay type</th>
                                    <th class="text-center">Status <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn"></button></th>
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
<script type="text/javascript" src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>
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
        var userId = 0;



        $('.startDate').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true
        });
        $('#user_id').change(function() {
            var s1 = $('#user_id').find("option:selected");
            var item = s1.val();
            userId = item;
            console.log('selected user');
            console.log(userId);


        });


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
            var optionSelected = $('#filter').find("option:selected");
            var item = optionSelected.val();
            $('#user-table tbody').remove();
            if (item == "1") {

                getData('1', '1', userId);

            }
            if (item == "2") {
                getData('2', '2', userId);
            }
            if (item == "3") {
                getData('3', '3', userId);
            }
            if (item == "4") {
                getData('4', '4', userId);
            }
            if (item == "5") {
                getData('5', '5', userId);
            }

        });


        // console.log(userId);



        function getData(startDate, endDate, id) {
            $.ajax({
                type: "GET",
                url: "{{ url('admin/report/overtime') }}" + '/' + id + '/' + startDate + '/' + endDate,
                dataType: 'json',
                beforeSend: function() {
                    console.log('before send'); // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $('#loader').removeClass('hidden')
                },
                success: function(data) {
                    // ajax response return data , so must data.data to get the value
                    console.log(data.data);

                    data.data.forEach((item, index) => {
                    if (item.send_status == "true") {
                        var element = `<tbody>
                                <tr>
                                    <td  class="text-center"></td>
                                    <td  class="text-center">${index+1}</td>
                                    <td  class="text-center">${item.user_name}</td>
                                    <td class="text-center">${item.position_name}</td>
                                    <td class="text-center">${item.reason}</td>
                                    <td  class="text-center">${item.from_date}</td>
                                    <td class="text-center">${item.type}</td>
                                    <td  class="text-center">${item.number}</td>
                                    <td class="text-center">${item.ot_rate} $</td>
                                    <td class="text-center">${item.ot_hour}</td>
                                    <td class="text-center">${item.ot_method}</td>
                                    <td class="text-center">${item.total_ot} $</td>
                                    <td class="text-center">${item.date}</td>
                                    <td class="text-center">${item.requested_by}</td>
                                    <td class="text-center">${item.pay_type}</td>
                                    <td class="text-center">${item.status}</td>
                                </tr>
                                </tbody>
                            `;
                    } else {
                        var element = `<tbody>
                                <tr>
                                    <td  class="text-center"><input type="checkbox" name="country_checkbox" data-id="${item.overtime_id}"></td>
                                    <td  class="text-center">${index+1}</td>
                                    <td  class="text-center">${item.user_name}</td>
                                    <td class="text-center">${item.position_name}</td>
                                    <td class="text-center">${item.reason}</td>
                                    <td  class="text-center">${item.from_date}</td>
                                    <td class="text-center">${item.type}</td>
                                    <td  class="text-center">${item.number}</td>
                                    <td class="text-center">${item.ot_rate} $</td>
                                    <td class="text-center">${item.ot_hour}</td>
                                    <td class="text-center">${item.ot_method}</td>
                                    <td class="text-center">${item.total_ot} $</td>
                                    <td class="text-center">${item.date}</td>
                                    <td class="text-center">${item.requested_by}</td>
                                    <td class="text-center">${item.pay_type}</td>
                                    <td class="text-center">${item.status}</td>
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
</script>
@endsection