<div class="navbar-bg" style="background-color: #34ace0"></div>
<!-- style="background-color: #445760" -->
<!-- background-color: #34ace0 -->
<nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav">
        <li>
            <a href="javascript:void(0)" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"> </i>

            </a>
        </li>
        @include('admin.layouts.title')
    </ul>
    <ul class="ml-auto navbar-nav navbar-right">
        <li class="dropdown">
            <a href="javascript:void(0)" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{asset('img/users/admin.jpg')}}" class="rounded-circle mr-1" width="30px" height="30px">
                <!-- <img alt="image" src="{{ asset('img/users/' . auth()->user()->profile_url) }}"
                    class="rounded-circle mr-1" width="30px" height="30px"> -->
                <!-- <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div> -->

            </a>


            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    class="dropdown-item has-icon text-dark">
                    <i class="fas fa-sign-out-alt"></i> Logout
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                </a>
                <a href="{{ url('admin/change-password') }}"
                   
                    class="dropdown-item has-icon text-dark">
                    <i class="fas fa-key"></i> Change password
                </a>
                <a href="{{ url('admin/user/change-password') }}"
                   
                    class="dropdown-item has-icon text-dark">
                    <i class="fas fa-key "></i> Change user password
                </a>
               
            </div>
            <!-- <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <a href=""
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                    class="dropdown-item has-icon text-danger">
                    <i class="fas fa-key"></i> Change password
                </a>

               
            </div> -->
        </li>
    </ul>
    <ul class=" navbar-nav navbar-right">
        <li class="dropdown">
            <a href="javascript:void(0)" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <!-- <img alt="image" src="{{asset('img/users/admin.jpg')}}" class="rounded-circle mr-1" width="30px" height="30px"> -->
                <!-- <img alt="image" src="{{ asset('img/users/' . auth()->user()->profile_url) }}"
                    class="rounded-circle mr-1" width="30px" height="30px"> -->
                <!-- <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->name }}</div> -->
                <!-- <i class="fas fa-globe">
    
                </i> -->
                {{ csrf_field() }}
                {{ __('item.language') }}
                <!--  -->
            </a>


            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <!-- get all language from config.php -->
                {{ csrf_field() }}
              
                <a class="dropdown-item" href="{{ url('admin/locale/en') }}"> {{ __('item.english') }}</a>
                <a class="dropdown-item" href="{{ url('admin/locale/kh') }}"> {{ __('item.khmer') }}</a>
                



            </div>
        </li>
    </ul>
</nav>