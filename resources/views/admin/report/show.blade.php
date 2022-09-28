@extends('admin.layouts.master')
@section('title', 'Rekap Absensi')

@section('css')
    <link rel="stylesheet" href="https://demo.getstisla.com/assets/modules/fullcalendar/fullcalendar.min.css">

@endsection

@section('content')
   <!-- Main Content -->
  <div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>Calendar</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item">
              <a href="{{ route('admin.dashboard') }}">
                  <i class="fa fa-home"></i>
                  Dashboard
              </a>
          </div>
          <div class="breadcrumb-item">
            <i class="fas fa-user"></i>
            Rekap Absensi
        </div>
      </div>
      </div>

      <div class="section-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Calendar</h4>
                {{-- {{ $x }} --}}
              </div>
              <div class="card-body">
                <div class="fc-overflow">                            
                  <div id="myEvent"></div>
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
  <script src="https://demo.getstisla.com/assets/modules/fullcalendar/fullcalendar.min.js"></script>
  <script>
    var data = <?= $x ?>
    // console.log(data)
    $("#myEvent").fullCalendar({
      height: 'auto',
      timeZone: 'local',
      header: {
        left: 'prev,next today',
        center: 'title',
      },
      editable: true,
      events: data
    });
  </script>
@endsection