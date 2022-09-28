@extends('admin.layouts.master')
@section('title', 'Employee')
@section('head','Employee')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
<!-- Modal -->

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <!-- <h1>Employee Data</h1> -->
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <!-- <a href="{{ url('admin/dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a> -->
                </div>
                <div class="breadcrumb-item">
                    <!-- <i class="fas fa-user"></i>
                    Employee List -->
                </div>
            </div>
        </div>
        <!-- alert error -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible show fade">
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
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#"><img alt="image" src="{{asset('img/users/admin.jpg')}}" class="rounded-circle mr-1" width="100px" height="100px"></a>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="profile-info-left">
                                        <h3 class="user-name m-t-0 mb-0">{{ $user->name }}</h3>
                                        <h6 class="text-muted"> {{ $user['department']->department_name }}</h6>
                                        <small class="text-muted">{{ $user['position']->position_name }}</small>
                                        <div class="staff-id">Employee ID : {{ $user->card_number }} </div>
                                        <div class="small doj text-muted">Date of Join : {{ $user->created_at }}</div>
                                        <div class="staff-msg">Timetable : 
                                            <li>
                                                Time in {{$user['timetable']->on_duty_time}}        
                                            </li>
                                            <li>
                                            Time out  {{$user['timetable']->off_duty_time}}
                                            </li>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Phone:</div>
                                            <div class="text"><a href="">{{ $user->employee_phone }}</a></div>
                                        </li>
                                        <li>
                                            <div class="title">Email:</div>
                                            <div class="text"><a href="">{{ $user->email }}</a></div>
                                        </li>
                                        <li>
                                            <div class="title">Birthday:</div>

                                            <div class="text">{{ $user->dob }}</div>

                                        </li>
                                        <li>
                                            <div class="title">Address:</div>

                                            <div class="text">{{ $user->address }}</div>

                                        </li>
                                        <li>
                                            <div class="title">Gender:</div>

                                            <div class="text">{{ $user->gender }}</div>

                                        </li>
                                        <li>
                                            <div class="title">Reports to:</div>
                                            <div class="text">
                                                <div class="avatar-box">

                                                </div>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Nationality:</div>
                                            <div class="text"><a href="">{{ $user->nationality }}</a></div>
                                        </li>
                                        <li>
                                            <div class="title">Office Tel:</div>
                                            <div class="text"><a href="">{{ $user->office_tel }}</a></div>
                                        </li>
                                        <li>
                                            <div class="title">Marital Status:</div>

                                            <div class="text">{{ $user->merital_status }}</div>

                                        </li>
                                        <li>
                                            <div class="title">Minor Children:</div>

                                            <div class="text">{{ $user->minor_children }}</div>

                                        </li>
                                        <li>
                                            <div class="title">Spouse Job:</div>

                                            <div class="text">{{ $user->spouse_job }}</div>

                                        </li>
                                        <li>
                                            <div class="title">Reports to:</div>
                                            <div class="text">
                                                <div class="avatar-box">

                                                </div>

                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    </div>
                    <div class="row">
                        
                        <div class="col-md-12">     
                            <h3 class="user-name m-t-0 mb-0"  style="margin-left:10px;margin-bottom:5px">Work Day</h3>
                        <div class="row">
                            @foreach($workDay as $r)
                                @if($r=="0")
                                <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;margin-left:30px">Sunday</p></div>
                                @elseif($r=="1")
                                <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Monday</p></div>
                                @elseif($r=="2")
                                <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Tuesday</p></div>
                                @elseif($r=="3")
                                <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Wednesday</p></div>
                                @elseif($r=="4")
                                <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Thurday</p></div>
                                @elseif($r=="5")
                                <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Friday</p></div>
                                @else
                                <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Saturday</p></div>
                                @endif
                            @endforeach  
                            @foreach($offDay as $r)
                                @if($r=="0")
                                <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Sunday</p></div>
                                @elseif($r=="1")
                                <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Monday</p></div>
                                @elseif($r=="2")
                                <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Tuesday</p></div>
                                @elseif($r=="3")
                                <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Wednesday</p></div>
                                @elseif($r=="4")
                                <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Thurday</p></div>
                                @elseif($r=="5")
                                <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Friday</p></div>
                                @else
                                <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;margin-right:5px;">Saturday</p></div>
                                @endif
                                                                   
                            @endforeach 
                        </div>  
                                       
                                                                                       
                                

                        </div>   
                        
                    </div>
                    <hr>
                    <!-- <div class="row">
                        <div class="col-md-12">     
                                <h3 class="user-name m-t-0 mb-0"  style="margin-left:10px;margin-bottom:5px">Payslip</h3>
                        </div>
                    </div> -->
                </div>
                <br>
                <br>

            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="{{ asset('backend/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>

<script>

</script>
@endsection