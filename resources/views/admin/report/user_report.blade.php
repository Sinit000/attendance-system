@extends('admin.layouts.master')
@section('title', 'Report')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
<!-- Modal -->


   <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Employee Data</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-user"></i>
                       Employee List
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

                        <div class="tile">
                            <div class="tile-body bg-white rounded overflow_hidden p-4">
                                <div class="rows">
                                    <form id="frmSubmit" method="GET" class="form-inline" action=" {{ url('customer_report') }}" target="_blank">
                                        @csrf
                                        <div class="col-md-4">
                                            {{ Form::text('start_date', $start_date ?? '', ['class' => 'width-100 form-control demoDate', 'autocomplete' => 'off', 'placeholder' => Lang::get('item.start_date')]) }}
                                        </div>
                                        <div class="col-md-4">
                                            {{ Form::text('end_date', $end_date ?? '', ['class' => 'width-100 form-control demoDate onchange-submit', 'autocomplete' => 'off', 'placeholder' => Lang::get('item.end_date')]) }}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::text('search',$request->search,['class'=>'width-100 form-control pull-right onchange-submit','placeholder'=> Lang::get('item.search')]) !!}
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
                                            <a href="#" onclick="$('#frmSubmit').submit()" class="btn btn-sm rounded p-1 btn-primary mt-1 pull-right"><i class="fa fa-search"></i> Filter</a>
                                        </div>

                                    </form>
                                   
                                    <br>
                                    <div class="col-sm-12">
                                        <button class="btn btn-small btn-success pull-right" id="btn_print" type="">{{ __('item.print') }}</button>
                                    </div>
                                </div>
                                <br>
                                <div id="table_print">
                                    <div class="text-success display_message text-center"></div><br>
                                    <div style="margin-left: 150px;"></div>
                                    <center>
                                        <div style="font-family: Khmer OS Muol light;font-size:20px;margin-top:-150px"></div>
                                        <div style="font-family: Khmer OS Muol light;font-size:20px;font-weight:bold"></div>
                                    </center>	
                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        
                                    </div>
                                </div>
                                <!-- talbe -->
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover" id="user-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="70" class="text-center">{{ __('item.no') }}</th>
                                                <th class="text-center">{{ __('item.date') }}</th>
                                                <th class="text-center">{{ __('item.name') }}</th>
                                                <th class="text-center">{{ __('item.gender') }}</th>
                                                <th class="text-center">{{ __('item.phone') }}</th>
                                                <th class="text-center">{{ __('item.email') }}</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($item as $key =>$row)
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td class="text-center">{{ $row->customer_date }}</td>
                                                <td class="text-center">{{ $row->customer_name }}</td>
                                            
                                                <td class="text-center">{{ $row->gender }}</td>
                                                <td class="text-center">{{ $row->employee_phone }}</td>
                                                <td class="text-center">{{ $row->email }}</td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                                
                                <!--  -->
                                </div>
                                <div class="pull-right">
                                    {{-- {!! $items->render() !!} --}}
                                    {{-- <a href="{{ $url_excel ?? '' }}" class="btn btn-success btn-sm" title="Excel"><i class="fa fa-file-excel-o m-r-0"></i></a>
                                    <a href="{{ route('report.pdf') }}" class="btn btn-info btn-sm" title="PDF"><i class="fa fa-file-pdf-o m-r-0"></i></a> --}}
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')

@endsection
