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
            <h1>Dashboard</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>×</span>
                    </button>
                    {!! session('success') !!}
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <!-- <div class="card">
              <div class="card-header">
                <h4>Welcome to Attendance App</h4>
              </div>

            </div> -->
                    <div class="row">

                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <form action="{{ url('admin/datetime/update') }}" method="POST" >
                                    @csrf
                                        <input type="hidden" name="cid" value="{{$data->id}}" id="cid">
                                        <input type="hidden" name="type_date_time" value="server" id="type_date_time">
                                        <div class="card-header">
                                            <h4>Server date</h4>
                                            <h4></h4>
                                            @if($data->type_date_time != 'server' )
                                            <button type="submit" class="btn btn-primary btn-round ml-auto" id="btn-server">

                                                Change
                                            </button>

                                            @else
                                            <p>Default</p>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="far fa-file"></i>
                                </div>
                                <div class="card-wrap">
                                    <form method="POST" action="{{ url('admin/datetime/update') }}"  >
                                    @csrf
                                        <input type="hidden" name="cid" value="{{$data->id}}" id="cid">
                                        <input type="hidden" name="type_date_time" value="computer" id="type_date_time">
                                    <div class="card-header">
                                        <h4>Comuter date</h4>
                                        <h4> </h4>
                                        @if($data->type_date_time == 'server' )
                                        <button type="submit" class="btn btn-danger btn-round ml-auto" id="btn-computer">

                                        <i class="fas fa-check"></i>
                                        Save
                                        </button>

                                        @else
                                        <p></p>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                    </form>
                                    

                                        <!-- <p>{{ $data->type_date_time ==='server' ? "This is a default value":'no' }}</p> -->


                                    </div>
                                </div>
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
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // key up function on form password
           
            $('form').submit(function() {
                $('#btn-server').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
                var id = $('#cid').val()
                console.log(id);
            });
            $('form').submit(function() {
                $('#btn-computer').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
                var id = $('#cid').val()
                console.log(id);
            });
            //  $('#addform').on('submit', function(e) {
            //         e.preventDefault();
            //         var form = this;
            //         console.log("adding");
            //         $.ajax({
            //             url: $(form).attr('action'),
            //             method: $(form).attr('method'),
            //             data: new FormData(form),
            //             processData: false,
            //             dataType: 'json',
            //             contentType: false,
            //             beforeSend: function() {
            //                 $(form).find('span.error-text').text('');
            //             },
            //             success: function(data) {
            //                 if (data.code == 1) {
            //                     console.log(data);

            //                     toastr.error(data.message);
            //                     console.log("toast1");

            //                 } else {
            //                     $(form)[0].reset();
            //                     //  alert(data.msg);
            //                     // $('#mytable').DataTable().ajax.reload(null, false);
            //                     // $('.addModal').modal('hide');
            //                     console.log("toast2");
            //                     toastr.success(data.message);
            //                     //location.href = "/products";
            //                     console.log("succee");
            //                 }
            //             }
            //         });
            // });

        });
    </script>
@endsection