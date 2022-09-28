@extends('admin.layouts.master')
@section('title', 'Dashboard')

@section('css')
    <link rel="stylesheet" href="https://demo.getstisla.com/assets/modules/fullcalendar/fullcalendar.min.css">

@endsection

@section('content')
   <!-- Main Content -->
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        {{-- <h1>ID Card</h1> --}}
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item">
              <a href="{{ route('admin.dashboard') }}">
                  <i class="fa fa-home"></i>
                  ID Card
              </a>
          </div>
      </div>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-lg-4 col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h4>ID Card</h4>
              </div>
              <div class="card-body text-center">
                {!! QrCode::size(150)->generate($user[0]->nip) !!}
                <br><br>  
                <h4>{{ $user[0]->name }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
