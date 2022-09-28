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
            <h1>Overtime</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-user"></i>
                    overtime List
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
                        <form id="frmSubmit" method="GET" class="form-inline" action=" {{ url('admin/report/overtime') }}" target="_blank">
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
                                    <th width="70" class="text-center">No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Position</th>
                                    <th class="text-center">Total Hour</th>
                                    <th class="text-center">Total Earning</th>
                                   

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item as $key =>$row)
                                <tr>
                                    <td class="text-center">{{ ++$key }}</td>
                                    <td class="text-center">{{ $row->user_name }}</td>
                                    <td class="text-center">{{ $row->position_name }}</td>

                                    <td class="text-center">{{ $row->hour }}</td>
                                    <td class="text-center">{{ $row->total }}</td>
                                    

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
</script>
@endsection