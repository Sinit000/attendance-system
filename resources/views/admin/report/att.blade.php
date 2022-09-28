@extends('admin.layouts.master')
@section('title', 'Attendance')

@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
<style>
    body {
        font-family: Arial;
    }

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

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

    /* employee */
    #employee-table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #employee-table td,
    #employee-table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #employee-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #employee-table tr:hover {
        background-color: #ddd;
    }

    #employee-table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
    }
</style>
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
                    <div class="ml-auto">
                        <a href="{{url('admin/reports')}}" class="btn btn-info ">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab">
                        <button class="tablinks"  onclick="openCity(event, 'London')">All Employees</button>
                        <button class="tablinks" id="defaultOpen" onclick="openCity(event, 'Paris')">Employee</button>
                        <!-- <button class="tablinks" onclick="openCity(event, 'Tokyo')">Tokyo</button> -->
                    </div>
                    <div id="London" class="tabcontent">
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="" class="form-control  select2" id="filter" style="width: 100%;">
                                        <option value="">Choose Value </option>
                                        <option value="1">Today</option>
                                        <option value="2">This week</option>
                                        <option value="3">This month</option>
                                        <option value="4">This year</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="startDate" name="demoDate" autocomplete="off" placeholder="Start Date">
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
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Employee name</th>
                                        <th class="text-center">Timetable</th>
                                        <th class="text-center">Checkin time</th>

                                        <th class="text-center">Late</th>
                                        <th class="text-center">Checkout time</th>

                                        <th class="text-center">Early</th>
                                        <th class="text-center">Status </th>

                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>

                    <div id="Paris" class="tabcontent">
                        <br>
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
                                    <select name="" class="form-control  select2" id="filter2" style="width: 100%;">
                                        <option value="">Choose Value </option>
                                        <option value="1">Today</option>
                                        <option value="2">This week</option>
                                        <option value="3">This month</option>
                                        <option value="4">This year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="startUserDate" name="demoDate" autocomplete="off" placeholder="Start Date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" id="endUserDate" name="demoDate" placeholder="End Date" autocomplete="off">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <button class="btn btn-small btn-dark pull-right" style="margin-right:20px;margin-left:20px;" id="btn_export" type="">Export to Excel</button>
                            <button class="btn btn-small btn-info pull-right" id="btn_print" type="">Print</button>

                        </div>

                        <br>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered" id="employee-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="main_checkbox"><label></label>
                                        </th>
                                        <th width="70" class="text-center">No</th>
                                        <th class="text-center">Date</th>

                                        <th class="text-center">Timetable</th>
                                        <th class="text-center">Checkin time</th>
                                        <th class="text-center">Checkin status</th>
                                        <th class="text-center">Late</th>
                                        <th class="text-center">Checkout time</th>
                                        <th class="text-center">Checkout status</th>
                                        <th class="text-center">Early</th>
                                        <th class="text-center">Status <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn"></button></th>


                                    </tr>
                                </thead>

                            </table>
                        </div>
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

<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    document.getElementById("defaultOpen").click();
    $(document).ready(function() {
        // Setup AJAX CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var statDate = "";

        var endDate = "";
        var endUserDate = "";
        var statUserDate = "";
        var userId = "";
        $('#startDate').change(function() {
            statDate = $('#startDate').val();

        });

        $('#endDate').change(function() {
            endDate = $('#endDate').val();

            $('#user-table tbody').remove();
            getData(statDate, endDate);

        });
        $('select').change(function() {
            var optionSelected = $(this).find("option:selected");

            var item = optionSelected.val();
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

        });

        function getData(startDate, endDate) {
            // get data



            // ajax get data
            $.get("{{ url('admin/report/attendances') }}" + '/' + startDate + '/' + endDate, function(response) {

                console.log(response.data);

                response.data.forEach((item, index) => {
                    var element = `<tbody>
                                <tr>

                                    <td  class="text-center">${index+1}</td>
                                    <td  class="text-center">${item.checkin_date}</td>
                                    <td class="text-center">${item.user_name}</td>
                                    <td class="text-center">${item.timetable_id}</td>
                                    <td  class="text-center">${item.checkin_time}</td>
                                    <td class="text-center">${item.checkin_late}</td>
                                    <td  class="text-center">${item.checkout_time}</td>
                                    <td class="text-center">${item.checkout_late}</td>
                                    <td class="text-center">${item.status}</td>
                                    
                                </tr>
                                </tbody>
                            `;

                    $('#user-table').append(element);
                });



            }).fail(function(e) {
                console.log(e);
            });
           
        }
        // $('#btn_print').click(function() {
        //     $('#user-table').printThis({
        //         importStyle: true,
        //         importCSS: true
        //     });
        // });
        // $('#btn_export').on('click', function() {
        //     console.log('send excel');
        //     // $serializeArray = serialize($array)
        //     console.log(dataList);
        //     var url = "{{URL::to('admin/report/export')}}" + '/' + dataList
        //     console.log(url);

        //     window.location.assign(url)
        // });

        // filter by employee
        $('#user_id').change(function() {
            var optionSelected = $(this).find("option:selected");

            var item = optionSelected.val();
            userId = item;
            console.log(item);

        });
        $('select').change(function() {
            var optionSelected = $(this).find("option:selected");

            var item = optionSelected.val();
            console.log(userId);
            $('#employee-table tbody').remove();
            if (item == "1") {

                getEmployeeData('1', '1', userId);
                console.log('call');

            }
            if (item == "2") {
                getEmployeeData('2', '2', userId);
                console.log('call');
            }
            if (item == "3") {
                getEmployeeData('3', '3', userId);
                console.log('call');
            }
            if (item == "4") {
                getEmployeeData('4', '4', userId);
                console.log('call');
            }

        });
        $('#startUserDate').change(function() {
            statUserDate = $('#startUserDate').val();

        });

        $('#endUserDate').change(function() {
            endUserDate = $('#endUserDate').val();

            $('#employee-table tbody').remove();
            getEmployeeData(statDate, endDate, userId);

        });

        function getEmployeeData(statUserDate, endUserDate, userId) {
            // get data
            console.log('sinit');
            console.log(userId);


                // ajax get data
                $.get("{{ url('admin/report/attendance/employee') }}" + '/' + userId + '/' + statUserDate + '/' + endUserDate, function(response) {


                    console.log(response.data);
                    response.data.forEach((item, index) => {
                        if (item.send_status == "true") {
                            var element = `<tbody>
                                    <tr>
                                    <td  class="text-center"></td>
                                        <td  class="text-center">${index+1}</td>
                                        <td  class="text-center">${item.checkin_date}</td>
                                    
                                        <td class="text-center">${item.timetable_id}</td>
                                        <td  class="text-center">${item.checkin_time}</td>
                                        <td  class="text-center">${item.checkin_status}</td>
                                        <td class="text-center">${item.checkin_late}</td>
                                        <td  class="text-center">${item.checkout_time}</td>
                                        <td  class="text-center">${item.checkout_status}</td>
                                        <td class="text-center">${item.checkout_late}</td>
                                        <td class="text-center">${item.status}</td>
                                        
                                    </tr>
                                    </tbody>
                                `;
                        } else {
                            var element = `<tbody>
                                    <tr>
                                    <td  class="text-center"><input type="checkbox" name="country_checkbox" data-id="${item.checkin_id}"></td>
                                        <td  class="text-center">${index+1}</td>
                                        <td  class="text-center">${item.checkin_date}</td>
                                    
                                        <td class="text-center">${item.timetable_id}</td>
                                        <td  class="text-center">${item.checkin_time}</td>
                                        <td  class="text-center">${item.checkin_status}</td>
                                        <td class="text-center">${item.checkin_late}</td>
                                        <td  class="text-center">${item.checkout_time}</td>
                                        <td  class="text-center">${item.checkout_status}</td>
                                        <td class="text-center">${item.checkout_late}</td>
                                        <td class="text-center">${item.status}</td>
                                        
                                    </tr>
                                    </tbody>
                                `;
                        }


                        $('#employee-table').append(element);
                    });



                }).fail(function(e) {
                    console.log(e);
                });
                // 
        }

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

        var url = '{{ url("admin/attendance/updates") }}';
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