<!-- Header -->
<header class="header d-flex flex-row" style="width:95%">
    <div class="header_content d-flex flex-row align-items-center">

        <!-- Logo -->
        <div class="logo_container">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="">
                <span>NPIC Library</span>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="main_nav_container">
            <div class="main_nav">
                <ul class="main_nav_list">
                    <li class="main_nav_item"><a href="{{ route('public.index') }}">Home</a></li>
                    <li class="main_nav_item"><a href="{{ route('public.ebooks') }}">E-Books</a></li>
                    <li class="main_nav_item"><a href="{{ route('public.books') }}">Books</a></li>
                    <li class="main_nav_item"><a href="{{ route('public.aboutUs') }}">About Us</a></li>
                    <li class="main_nav_item"><a href="{{ route('public.contact') }}">Contact</a></li>
                    @auth
                        <li class="main_nav_item">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-history" style="font-size: 20px"></i>
                                @if($total !== null || $total > 0)
                                    <span class="badge badge-pill badge-danger">
                                        {{ $total }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <h3 class="dropdown-header text-center"><i class="fa fa-history"></i> History List</h3>
                                <div class="dropdown-divider"></div>
                                @if(!is_null($issues))
                                    @if($issues->isNotEmpty())
                                        @foreach($issues as $issue)
                                            @if($loop->iteration == 3)
                                                @break
                                            @endif
                                            @foreach($issue->issueItems as $issueItem)
                                                @if($loop->iteration == 2)
                                                    @break
                                                @endif
                                                <div class="container row pl-4 pr-4">
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('/img/books/' . $issueItem->book->book_cover_url) }}" class="img-thumbnail">
                                                    </div>
                                                    <div class="col-md-10">
                                                        <a href="{{ route('public.bookDetail', $issueItem->book->id) }}" class="float-left">{{ $issueItem->book->title }}{{ $issueItem->book->year ? ' - ' . $issueItem->book->year : '' }}</a>
                                                        @php
                                                            $diff = \Carbon\Carbon::parse($issueItem->due_date)->diffInDays(\Carbon\Carbon::today(), false)
                                                        @endphp

                                                        @if($diff > 0 && $issueItem->status == 'BORROW')
                                                            <span class="float-right badge badge-pill badge-warning">LATE</span>
                                                        @else
                                                            <span class="float-right badge badge-pill @if($issueItem->status == 'BORROW' || $issueItem->status == 'RETURN')
                                                                badge-success
                                                            @elseif($issueItem->status == 'LOST')
                                                                badge-danger
                                                            @endif">{{ $issueItem->status }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @else
                                        <div class="container-fluid">
                                            <i class="fa fa-book" style="font-size: 20px"></i>
                                            No History
                                        </div>
                                    @endif
                                @else
                                    <div class="container-fluid">
                                        <i class="fa fa-book" style="font-size: 20px"></i>
                                        No History
                                    </div>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item btn-block text-center" href="{{ route('public.history') }}"><i class="fa fa-external-link-alt"></i> Load More</a>
                            </div>
                        </li>
                        <li class="main_nav_item">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user-circle" style="font-size: 20px"></i>
                                <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <div class="container-fluid">
                                    <i class="fa fa-user-circle" style="font-size: 20px"></i>
                                    {{ auth()->user()->name }}
                                </div>
                                <hr>
                                <a href="{{ route('public.changepassword') }}" class="dropdown-item"><i class="fa fa-lock"></i> Change Password</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                                    <i class="fa fa-sign-out-alt"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="main_nav_item"><a href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </div>
        </nav>
    </div>
    <style>
        #search-parent {
            padding-top: 30px !important;
            transition: all 0.5s;
        }
        form.example input[type=text] {
            padding: 10px;
            font-size: 17px;
            border: 1px;
            float: left;
            width: 80%;
            background: #f1f1f1;
        }

        form.example button {
            float: left;
            width: 20%;
            padding: 10px;
            background: #FBB917;
            color: white;
            font-size: 17px;
            border: 1px;
            border-left: none;
            cursor: pointer;
        }
    </style>
    <div class="header_side align-items-center" id="search-parent">
        <form class="example" action="javascript:void(0)" style="margin:auto;margin-left: 15px;margin-right: 15px" id="search-form">
            <input type="text" placeholder="Search Book by Title, Author, Category, Publisher and Rack..." name="result" id="result" value="{{ request()->query('result') }}">
            <button type="submit" id="btnSubmit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <!-- Hamburger -->
    <div class="hamburger_container">
        <i class="fas fa-bars trans_200"></i>
    </div>

</header>

<!-- Menu -->
<div class="menu_container menu_mm">

    <!-- Menu Close Button -->
    <div class="menu_close_container">
        <div class="menu_close"></div>
    </div>

    <!-- Menu Items -->
    <div class="menu_inner menu_mm">
        <div class="menu menu_mm">
            <ul class="main_nav_list">
                <li class="main_nav_item"><a href="{{ route('public.index') }}">Home</a></li>
                <li class="main_nav_item"><a href="{{ route('public.ebooks') }}">E-Books</a></li>
                <li class="main_nav_item"><a href="{{ route('public.books') }}">Books</a></li>
                <li class="main_nav_item"><a href="{{ route('public.aboutUs') }}">About Us</a></li>
                <li class="main_nav_item"><a href="{{ route('public.contact') }}">Contact</a></li>
                @auth
                    <li class="main_nav_item">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-history" style="font-size: 20px"></i>
                            @if($total !== null || $total > 0)
                                <span class="badge badge-pill badge-danger">
                                    {{ $total }}
                                </span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <h3 class="dropdown-header text-center"><i class="fa fa-history"></i> History List</h3>
                            <div class="dropdown-divider"></div>
                            @if(!is_null($issues))
                                @if($issues->isNotEmpty())
                                    @foreach($issues as $issue)
                                        @if($loop->iteration == 3)
                                            @break
                                        @endif
                                        @foreach($issue->issueItems as $issueItem)
                                            @if($loop->iteration == 2)
                                                @break
                                            @endif
                                            <div class="container row pl-4 pr-4">
                                                <div class="col-md-3 col-sm-3 col-2">
                                                    <img src="{{ asset('/img/books/' . $issueItem->book->book_cover_url) }}" class="img-thumbnail">
                                                </div>
                                                <div class="col-md-9 col-sm-9 col-10">
                                                    <a href="{{ route('public.bookDetail', $issueItem->book->id) }}" class="float-left">{{ $issueItem->book->title }}{{ $issueItem->book->year ? ' - ' . $issueItem->book->year : '' }}</a>
                                                    @php
                                                        $diff = \Carbon\Carbon::parse($issueItem->due_date)->diffInDays(\Carbon\Carbon::today(), false)
                                                    @endphp

                                                    @if($diff > 0 && $issueItem->status == 'BORROW')
                                                        <span class="float-right badge badge-pill badge-warning">LATE</span>
                                                    @else
                                                        <span class="float-right badge badge-pill @if($issueItem->status == 'BORROW' || $issueItem->status == 'RETURN')
                                                            badge-success
                                                        @elseif($issueItem->status == 'LOST')
                                                            badge-danger
                                                        @endif">{{ $issueItem->status }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    <div class="container-fluid">
                                        <i class="fa fa-book" style="font-size: 20px"></i>
                                        No History
                                    </div>
                                @endif
                            @else
                                <div class="container-fluid">
                                    <i class="fa fa-book" style="font-size: 20px"></i>
                                    No History
                                </div>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item btn-block text-center" href="{{ route('public.history') }}"><i class="fa fa-external-link-alt"></i> Load More</a>
                        </div>
                    </li>
                    <li class="main_nav_item">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user-circle" style="font-size: 20px"></i>
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <div class="container-fluid">
                                <i class="fa fa-user-circle" style="font-size: 20px"></i>
                                {{ auth()->user()->name }}
                            </div>
                            <hr>
                            <a href="{{ route('public.changepassword') }}" class="dropdown-item"><i class="fa fa-lock"></i> Change Password</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                                <i class="fa fa-sign-out-alt"></i>
                                {{ __('Logout') }}
                            </a>
                        </div>
                    </li>
                    <li class="main_nav_item">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-search" style="font-size: 20px"></i>
                            <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="width:100% !important">
                            <form class="example" action="{{ route('public.search') }}" style="margin:auto;margin-left: 15px;margin-right: 15px">
                                <input type="text" placeholder="Search by Title, Author, Category, Publisher and Rack..." name="result" value="{{ request()->query('result') }}">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </li>
                @else
                    <li class="main_nav_item"><a href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</div>

