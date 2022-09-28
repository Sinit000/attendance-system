@extends('admin.layouts.master')
@section('title', 'QR')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
<!-- Modal -->

   <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>QR Code for Scan Attendance</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-user"></i>
                       Password
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="card card-info">
                    <!-- <div class="card-header">
                        <button class="btn btn-primary ml-auto" id="btn-add">
                            <i class="fas fa-plus-circle"></i>
                            QR for Scan Attendance
                        </button>
                    </div> -->
                    <div class="card-body">
                        <form id="company-form" method="GET" action="{{ route('admin/system/employee/pdf') }}" target="_blank">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Employee <sup class="text-danger">*</sup></label>
                                            <select class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                                                <option value="">Choose employee</option>
                                                        @foreach($employees as $employee)
                                                            <option value="{{ $employee->id }}" >{{ $employee->name }}</option>
                                                        @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="valid-department"></div>
                                    </div>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nip">New password <sup class="text-danger">*</sup></label>
                                        <input type="date" name="from" class="form-control" required="" >
                                        <div class="invalid-feedback" id="valid-name"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">To Date <sup class="text-danger">*</sup> </label>
                                        <input type="date" name="to" class="form-control" required="" >
                                        <div class="invalid-feedback" id="valid-type"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>   
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')

@endsection
