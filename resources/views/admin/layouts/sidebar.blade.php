<div class="main-sidebar sidebar-style-2" >
<!-- background-color: #AED6F1 -->
    <aside id="sidebar-wrapper" >
        <div class="sidebar-brand">
            Admin Panel
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            
        </div>
        <ul class="sidebar-menu" >

                <li class="{{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}" class="nav-link" ><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
                </li>
                
                @if(auth()->user()->role->name === "Admin")
                <li class="{{ request()->segment(2) == 'user' ? 'active' : '' }}">
                    <a href="{{ url('admin/user') }}" class="nav-link"><i class="fas fa-users"></i> <span>Employee</span></a>
                </li>
                @endif
                <!-- <li class="{{ request()->segment(2) == 'schedule' ? 'active' : '' }}">
                    <a href="{{ url('admin/schedule') }}" class="nav-link"><i class="fas fa-calendar"></i> <span>Employee Schedule</span></a>
                </li> -->
                @if(auth()->user()->role->name === "Admin")
                <li class="{{ request()->segment(2) == 'attendances' ? 'active' : '' }}">
                    <a href="{{ url('admin/attendances') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> <span>Attendance</span></a>
                </li>
                @endif
               
                <!-- approval board -->
                @if(auth()->user()->role->name === "Admin")
                <li  class="nav-item dropdown ">

                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-chart-bar"></i> <span>Approval Board</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->segment(2) == 'overtimes' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/approve/leaves') }}">Leave</a></li>
                        <li class="{{ request()->segment(2) == 'leaveouts' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/approve/leaveouts') }}">Leave out</a></li>
                        <li class="{{ request()->segment(2) == 'compesation' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/approve/overtimecompesations') }}">Overtime Compestion</a></li>
                        <li class="{{ request()->segment(2) == 'dayoff' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/approve/changedayoffs') }}">Notification Chage</a></li>

                    </ul>
                </li>
                @endif
                <!--  -->
                 <!-- <li class="{{ request()->segment(2) == 'approve' ? 'active' : '' }}">
                    <a href="{{ url('admin/approve') }}" class="nav-link"><i class="fas fa-users"></i> <span>Approve Leave</span></a>
                </li> -->
                @if(auth()->user()->role->name === "Admin")
                <li class="{{ request()->segment(2) == 'overtimes' ? 'active' : '' }}">
                    <a href="{{ url('admin/overtimes') }}" class="nav-link"><i class="fas fa-users"></i> <span>Overtime</span></a>
                </li> 
                @endif
                <!-- <li class="{{ request()->segment(2) == 'compesation' ? 'active' : '' }}">
                    <a href="{{ url('admin/overtimecompestions') }}" class="nav-link"><i class="fas fa-users"></i> <span>Overtime Compestion</span></a>
                </li>  -->
                @if(auth()->user()->role->name === "Admin")
                <li  class="nav-item dropdown ">

                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-chart-bar"></i> <span>Payroll</span></a>
                    <ul class="dropdown-menu">

                        
                        <li class="{{ request()->segment(2) == 'structure' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/structure') }}">Structure</a></li>
                        <li class="{{ request()->segment(2) == 'contract' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/contract') }}">Contract</a></li>
                        <li class="{{ request()->segment(2) == 'payslip' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/payslip') }}">Payslip</a></li>
                        <li class="{{ request()->segment(2) == 'ot_acc' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/report/accountant/overtime') }}">Overtime Report</a></li>
                        <li class="{{ request()->segment(2) == 'att_acc' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/report/accountant/attendances') }}">Attendance Report</a></li>
                        <li class="{{ request()->segment(2) == 'employ_acc' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/report/accountant/employees') }}">Employee Report</a></li>

                    </ul>
                </li>
                @else
                <li  class="nav-item dropdown ">

                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-chart-bar"></i> <span>Payroll</span></a>
                    <ul class="dropdown-menu">

                        
                        <!-- <li class="{{ request()->segment(2) == 'structure' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/structure') }}">Structure</a></li> -->
                        <!-- <li class="{{ request()->segment(2) == 'contract' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/contract') }}">Contract</a></li> -->
                        <li class="{{ request()->segment(2) == 'payslip' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/payslip') }}">Payslip</a></li>
                        <li class="{{ request()->segment(2) == 'ot_acc' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/report/accountant/overtime') }}">Overtime Report</a></li>
                        <li class="{{ request()->segment(2) == 'att_acc' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/report/accountant/attendances') }}">Attendance Report</a></li>

                    </ul>
                </li>
                @endif
                @if(auth()->user()->role->name === "Admin")
                <li class="{{ request()->segment(2) == 'reports' ? 'active' : '' }}">
                    <a href="{{ url('admin/reports') }}" class="nav-link"><i class="fas fa-chart-bar"></i> <span>Report</span></a>
                </li>
                @endif
               
                @if(auth()->user()->role->name === "Admin")

                <li  class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Setting</span></a>
                    <ul class="dropdown-menu">
                    
                        <li class="{{ request()->segment(2) == 'qr' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/qr') }}">QR</a></li>
                        <li class="{{ request()->segment(2) == 'location' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/location') }}">Location</a></li>
                        <li class="{{ request()->segment(2) == 'workday' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/workday') }}">Worday</a></li>
                        <li class="{{ request()->segment(2) == 'workday' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/datetime') }}">Datetime</a></li>
                        <li class="{{ request()->segment(2) == 'department' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/department') }}">Department</a></li>
                        <li class="{{ request()->segment(2) == 'position' ? 'active' : '' }}" ><a class="nav-link " href="{{ url('admin/position') }}">{{ __('item.position') }}</a></li>
                        <li class="{{ request()->segment(2) == 'timetable' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/timetable') }}">Timetable</a></li>
                        <li class="{{ request()->segment(2) == 'leavetype' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/leavetype') }}">Leavetype</a></li>
                        <li class="{{ request()->segment(2) == 'holiday' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/holiday') }}">Holiday</a></li>
                        <li class="{{ request()->segment(2) == 'notification' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/notification') }}">Notification</a></li>
                        <li class="{{ request()->segment(2) == 'counter' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/counter') }}">Counter</a></li>
                        <!-- <li class="{{ request()->segment(2) == 'ad' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/change-password') }}">Reset password</a></li>
                        <li class="{{ request()->segment(2) == 'us' ? 'active' : '' }}"><a class="nav-link" href="{{ url('admin/user/change-password') }}">Reset user password</a></li> -->
                    </ul>
                </li>
                
                @endif
        </ul>
    </aside>
</div>
