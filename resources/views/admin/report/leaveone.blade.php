@extends('admin.layouts.master')
@section('title', 'Report')

@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/css/bootstrap-datepicker.standalone.min.css" integrity="sha512-ZgpiugtcWdV2LX1a1uy6ckVtJ8J3W7VBgYpKzyqmJ0UFJef1biYdOlOM2hl35gkf9ki56WoUeDQlIRqgdUhKOQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection

@section('content')
<!-- Modal -->


<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>System Report</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    System
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card card-info">
                <div class="card-header">
                    <!-- <button class="btn btn-info ml-auto" id="btn-add">
                            <i class="fas fa-plus-circle"></i>
                            Create Data
                        </button> -->
                </div>
                <div class="card-body">
                    <div class="rows">
                        <form id="frmSubmit" method="GET" class="form-inline" action=" {{ url('admin/report/leaves') }}" target="_blank">
                            @csrf

                            <div class="col-md-4">
                                {{ Form::text('start_date', $start_date ?? '', ['class' => 'width-100 form-control demoDate', 'autocomplete' => 'off', 'placeholder' => Lang::get('item.start_date')]) }}
                            </div>
                            <div class="col-md-4">
                                {{ Form::text('end_date', $end_date ?? '', ['class' => 'width-100 form-control demoDate onchange-submit', 'autocomplete' => 'off', 'placeholder' => Lang::get('item.end_date')]) }}
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::text('search', $request->search,['class' => 'width-100 form-control search', 'placeholder'=>__('item.search')]) }}
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                {{Form::hidden('between_date',null,['id'=>'between_date'])}}
                                <a href="#" id="btnToday" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> Today</a>
                                <a href="#" id="btnYesterday" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> Yesterday</a>
                                <a href="#" id="btnThisWeek" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> This Week</a>
                                <a href="#" id="btnLastWeek" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> Last Week</a>
                                <a href="#" id="btnThisMonth" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> This Month</a>
                                <a href="#" id="btnLastMonth" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> Last Month</a>
                                <a href="#" id="btnThisYear" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> This Year</a>
                                <a href="#" id="btnLastYear" class="btn btn-sm rounded p-1 btn-outline-dark mt-1"><i class="fa fa-calendar-o"></i> Last Year</a>
                                <a href="#" onclick="$('#frmSubmit').submit()" class="btn btn-sm rounded p-1 btn-info mt-1 pull-right"><i class="fa fa-search"></i> Filter</a>
                            </div>

                        </form>

                        <br>
                        <div class="col-sm-12">
                            <button class="btn btn-small btn-info pull-right" id="btn_print" type="">Print</button>
                        </div>
                        <br>
                    </div>
                    <div class="row">
                        <div class="pull-right">
                            {{-- {!! $items->render() !!} --}}
                            {{-- <a href="{{ $url_excel ?? '' }}" class="btn btn-success btn-sm" title="Excel"><i class="fa fa-file-excel-o m-r-0"></i></a>
                            <a href="{{ route('report.pdf') }}" class="btn btn-info btn-sm" title="PDF"><i class="fa fa-file-pdf-o m-r-0"></i></a> --}}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered" id="user-table">
                            <thead class="thead-light">
                                <tr>
                                <th><input type="checkbox" name="main_checkbox"><label></label></th>
                                    <th width="70" class="text-center">No</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Employee name</th>
                                    <th class="text-center">Leavetype</th>
                                    <th class="text-center">Reason</th>
                                    <th class="text-center">From Date</th>
                                    <!-- <th class="text-center">Checkin status</th> -->
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Duration</th>
                                    <!-- <th class="text-center">Checkout status</th> -->
                                    <th class="text-center">Request Date</th>
                                    <th class="text-center">Leave Deduction</th>
                                    <th class="text-center">Status <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Send to Accountant</button></th>
                                    

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item as $key =>$row)
                                <tr>
                                    <td>
                                        <div>
                                            <input type="checkbox" name="country_checkbox" data-id="{{$row->id}}">
                                        </div>
                                    </td>
                                    <td class="text-center">{{ ++$key }}</td>
                                    <td class="text-center">{{ $row->checkin_date }}</td>
                                    <td class="text-center">{{ $row->user_name }}</td>
                                    
                                    <td class="text-center">{{ $row->typename }}</td>
                                    <td class="text-center">{{ $row->reason }}</td>
                                   
                                    <td class="text-center">{{ $row->from_date }}</td>
                                    <td class="text-center">{{ $row->type }}</td>
                                    <!-- <td class="text-center">{{ $row->checkin_status }}</td> -->
                                    <td class="text-center">{{ $row->number }}</td>
                                    <td class="text-center">{{ $row->date }}</td>
                                    <!-- <td class="text-center">{{ $row->checkout_status }}</td> -->
                                    <td class="text-center">{{ $row->leave_deduction }}</td>
                                    <td class="text-center">{{ $row->status }}</td>

                                </tr>
                                @endforeach
                            </tbody>
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
<script type="text/javascript" src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/printThis.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#btn_print').click(function() {
            $('#user-table').printThis({
                importStyle: true,
                importCSS: true
            });
        });
        $('.demoDate').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true
        });

        $('#btnYesterday').click(function() {
            $('#between_date').val('yesterday');
            $('#frmSubmit').submit();
        });
        $('#btnToday').click(function() {
            $('#between_date').val('today');
            $('#frmSubmit').submit();
        });
        $('#btnThisWeek').click(function() {
            $('#between_date').val('this_week');
            $('#frmSubmit').submit();
        });
        $('#btnThisMonth').click(function() {
            $('#between_date').val('this_month');
            $('#frmSubmit').submit();
        });
        $('#btnLastWeek').click(function() {
            $('#between_date').val('last_week');
            $('#frmSubmit').submit();
        });
        $('#btnLastMonth').click(function() {
            $('#between_date').val('last_month');
            $('#frmSubmit').submit();
        });
        $('#btnThisYear').click(function() {
            $('#between_date').val('this_year');
            $('#frmSubmit').submit();
        });
        $('#btnLastYear').click(function() {
            $('#between_date').val('last_year');
            $('#frmSubmit').submit();
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
                $.post(url, {
                            countries_ids: checkedCountries
                        }, function(data) {
                            console.log(data);
                            if (data.code == 0) {
                                $('#user-table').DataTable().draw(false);
                                $('#user-table').DataTable().on('draw', function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });

                                swal({
                                    title: "Success!",
                                    text: "Data has been update successfully!",
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
            //     $.ajax({
            //     type: type,
            //     url: ajaxurl,
            //     data: formData,
            //     dataType: 'json',
            //     success: function(data) {
            //         console.log(state);
                   
            //     },
            //     error: function(data) {

            //         try {
                       
            //         } catch {
                        
            //         }
            //     }
            // });
                // swal({
                //     title: 'Are you sure?',
                //     html: 'You want to update <b>(' + checkedCountries.length + ')</b> countries',
                //     showCancelButton: true,
                //     showCloseButton: true,
                //     confirmButtonText: 'Yes, Update',
                //     cancelButtonText: 'Cancel',
                //     confirmButtonColor: '#556ee6',
                //     cancelButtonColor: '#d33',
                //     width: 300,
                //     allowOutsideClick: false
                // }).then(function(result) {
                //     console.log('sinit');
                //     if (result.value) {
                //         console.log('work or not');
                //         // pus url 
                        
                //     }
                // })
            }
        });
</script>
@endsection