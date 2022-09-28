@extends('admin.layouts.master')
@section('title', 'Izin')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Izin</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-file-pdf"></i>
                        Izin
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-primary alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {!! session('success') !!}
                    </div>
                </div>
            @endif

            <div class="section-body">
                <div class="card card-primary">  
                    <div class="card-body">
                        <form action="{{ route('admin.permission.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="izin">Izin</label>
                                        <select class="select2 form-control form-control-sm @error('izin') is-invalid @enderror" name="izin" id="izin" required>
                                            <option value="" selected disabled>-- Pilih Izin --</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Cuti">Cuti</option>
                                        </select>
                                        <div class="invalid-feedback" id="valid-izin">{{ $errors->first('izin') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="catatan">Catatan</label>
                                        <input type="text" class="form-control @error('catatan') is-invalid @enderror" name="catatan" id="catatan" value="{{ old('catatan') }}" placeholder="Input here...">
                                        <div class="invalid-feedback" id="valid-catatan">{{ $errors->first('catatan') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-round" id="btn-submit">
                                        <i class="fas fa-check"></i>
                                        Submit 
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection