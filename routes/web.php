<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\DepartmentControler;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeavetypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\StructuretypeController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TimetableEmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkdayController;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


Auth::routes();
// Auth::routes(['register' => false, 'reset' => false]);
// Route::get('/', function () {

//    return view('welcome');
// })->name('welcome');
// Route::get('/', [LoginController::class,'adminLogin'])->name('adminLogin');


// Route::get('/admin/login', [LoginController::class,'adminLogin'])->name('admin/login');

// Route::get('/', 'AbsenController@index')->name('home');
// Route::get('/attendance/in', 'AbsenController@in')->name('scan.masuk');
// Route::get('/attendance/out', 'AbsenController@out')->name('scan.pulang');
// Route::get('/attendance/in/{id}', 'AbsenController@checkin')->name('in');
// Route::get('/attendance/out/{id}', 'AbsenController@checkout')->name('out');
Route::get('upload',function(){
    return view('upload');
});
Route::post('upload',function(Request $request){
    $uploadedFileUrl = Cloudinary ::upload($request->file('file')->getRealPath(),[
        'folder'=>'employee'
    ])->getSecurePath();
    $data = User::find(2);
    $data->profile_url = $uploadedFileUrl;
    $data->update();

});
Route::get('/admin/login', [LoginController::class, 'adminLogin'])->name('adminLogin');

// Route::get('/login', [LoginController::class,'adminLogin'])->name('login');
Route::get('/', function () {
    return redirect('/admin/login');
});
// Route::resource('absen', 'AbsenController');
// Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => [LanguageController::class,'switchLang']]);
// ROUTE FOR ADMIN ONLY
Route::name('admin/')->prefix('admin')->middleware(['auth', 'admin', 'active', 'check.session'])->group(function () {
    Route::get('locale/{locale}', function ($locale) {
        Session::put('locale', $locale);
        Session::save();
        return redirect()->back();
    });
    // Dashboard
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('attendances', [AdminController::class, 'attendance']);
    Route::post('attendances/checkin/{userId}', [AdminController::class, 'checkin']);
    Route::put('attendances/checkout/{checkinId}', [AdminController::class, 'checkout']);
    Route::get('qr', [AdminController::class, 'qr']);
    Route::get('imageQr', [QrController::class, 'imageQr']);
    //    admin report
    Route::get('reports', function () {
        return view('admin.report.report_admin');
    });
    Route::get('report/system/pdf', [AdminController::class, 'systemReport'])->name('system/report/pdf');
    // Route::get('report/employee', [AdminController::class,'employee']);
    Route::get('report/employee/pdf', [AdminController::class, 'employeeReport'])->name('system/employee/pdf');
    // Route::get('card', 'AdminController@card')->name('card');
    Route::get('report/system', [AdminController::class, 'report'])->name('system_report');
    // Route::get('report/employee', [AdminController::class,'employee'])->name('user_report');
    Route::get('reports/attendances', [AdminController::class, 'viewAttendance'])->name('system_report');
    Route::get('report/attendance/view', [AdminController::class, 'viewAttendanceAll'])->name('system_report');
    Route::get('report/attendances/{startDate}/{endDate} ', [AdminController::class, 'attendanceReport'])->name('user_report');
    Route::get('report/attendance/employee/{id}/{startDate}/{endDate} ', [AdminController::class, 'attendanceEmployee'])->name('user_report');
    Route::get('reports/employee', [AdminController::class, 'viewEmployee'])->name('user_report');
    Route::get('report/employee/{startDate}/{endDate} ', [AdminController::class, 'employee'])->name('user_report');
    // send employee to account
    Route::post('employee/updates', [AdminController::class, 'sendEmployee']);
    Route::get('report/test', [AdminController::class, 'test'])->name('user_report');
    Route::get('report/export{list}', [AdminController::class, 'export'])->name('user_report');
    Route::get('reports/attendance', [AdminController::class, 'attendanceReport']);
    Route::get('report/leaves', [AdminController::class, 'leaveView'])->name('user_report');
    Route::get('report/leaves/{id}/{startDate}/{endDate}', [AdminController::class, 'leaveReport'])->name('user_report');
    Route::get('reports/overtimes', [AdminController::class, 'overtimeView'])->name('user_report');
    Route::get('report/overtime/{id}/{startDate}/{endDate}', [AdminController::class, 'overtimeReport'])->name('user_report');
    // send to account
    Route::post('attendance/updates', [AdminController::class, 'sendAttencanetoAccount']);
    // admn cofirm leave
    Route::post('leave/updates', [ApproveController::class, 'confirmLeave']);

    // Data Karyawan
    Route::get('user', [UserController::class, 'index'])->name('user');
    Route::get('user/details/{id}', [UserController::class, 'detail'])->name('user');
    Route::get('user/create', [UserController::class, 'create']);
    Route::post('user/store', [UserController::class, 'store']);
    Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
    Route::get('user/edit/{id}', [UserController::class, 'edit']);
    Route::post('user/update/{id}', [UserController::class, 'update']);
    // workday
    Route::get('datetime', [WorkdayController::class, 'showdate']);
    Route::post('datetime/update', [WorkdayController::class, 'updateDate']);
    Route::get('workday', [WorkdayController::class, 'index']);
    Route::post('workday/store', [WorkdayController::class, 'store']);
    Route::delete('workday/delete/{id}', [WorkdayController::class, 'destroy']);
    Route::get('workday/edit/{id}', [WorkdayController::class, 'edit']);
    Route::put('workday/update/{id}', [WorkdayController::class, 'update']);
    // location
    Route::get('location', [LocationController::class, 'index']);
    Route::post('location/store', [LocationController::class, 'store']);
    Route::delete('location/delete/{id}', [LocationController::class, 'destroy']);
    Route::get('location/edit/{id}', [LocationController::class, 'edit']);
    Route::put('location/update/{id}', [LocationController::class, 'update']);

    // position
    Route::get('position', [PositionController::class, 'index']);
    Route::post('position/store', [PositionController::class, 'store']);
    Route::delete('position/delete/{id}', [PositionController::class, 'destroy']);
    Route::get('position/edit/{id}', [PositionController::class, 'edit']);
    Route::put('position/update/{id}', [PositionController::class, 'update']);
    // holiday
    Route::get('holiday', [HolidayController::class, 'index']);
    Route::post('holiday/store', [HolidayController::class, 'store']);
    Route::delete('holiday/delete/{id}', [HolidayController::class, 'destroy']);
    Route::get('holiday/edit/{id}', [HolidayController::class, 'edit']);
    Route::put('holiday/update/{id}', [HolidayController::class, 'update']);

    // notification
    Route::get('notification', [NotificationController::class, 'index']);
    Route::get('notification/componet', [NotificationController::class, 'getComponent']);
    Route::post('notification/store', [NotificationController::class, 'store']);
    Route::delete('notification/delete/{id}', [NotificationController::class, 'destroy']);
    Route::get('notification/edit/{id}', [NotificationController::class, 'edit']);
    Route::put('notification/update/{id}', [NotificationController::class, 'update']);

    // timetable
    Route::get('timetable', [TimetableController::class, 'index']);

    Route::post('timetable/store', [TimetableController::class, 'store']);
    Route::delete('timetable/delete/{id}', [TimetableController::class, 'destroy']);
    Route::get('timetable/edit/{id}', [TimetableController::class, 'edit']);
    Route::put('timetable/update/{id}', [TimetableController::class, 'update']);

    // leavetype
    Route::get('leavetype', [LeavetypeController::class, 'index']);
    Route::get('leavetype/componet', [LeavetypeController::class, 'getComponent']);
    Route::post('leavetype/store', [LeavetypeController::class, 'store']);
    Route::delete('leavetype/delete/{id}', [LeavetypeController::class, 'destroy']);
    Route::get('leavetype/edit/{id}', [LeavetypeController::class, 'edit']);
    Route::put('leavetype/update/{id}', [LeavetypeController::class, 'update']);

    // department
    Route::get('department', [DepartmentControler::class, 'index']);
    Route::get('department/componet', [DepartmentControler::class, 'getComponent']);
    Route::post('department/store', [DepartmentControler::class, 'store']);
    Route::delete('department/delete/{id}', [DepartmentControler::class, 'destroy']);
    Route::get('department/edit/{id}', [DepartmentControler::class, 'edit']);
    Route::put('department/update/{id}', [DepartmentControler::class, 'update']);

    // employee schedule
    Route::get('schedule', [TimetableEmployeeController::class, 'index']);
    Route::get('schedule/componet', [TimetableEmployeeController::class, 'getComponent']);
    Route::post('schedule/store', [TimetableEmployeeController::class, 'store']);
    Route::delete('schedule/delete/{id}', [TimetableEmployeeController::class, 'destroy']);
    Route::get('schedule/edit/{id}', [TimetableEmployeeController::class, 'edit']);
    Route::put('schedule/update/{id}', [TimetableEmployeeController::class, 'update']);

    // approve leave
    Route::get('approve/leaves', [ApproveController::class, 'index']);
    Route::get('approve/leaves/edit/{id}', [ApproveController::class, 'edit']);
    Route::put('approve/leaves/update/{id}', [ApproveController::class, 'update']);
    Route::get('approve/overtimecompesations', [ApproveController::class, 'getOtCompesation']);
    Route::get('approve/overtimecompesations/edit/{id}', [ApproveController::class, 'editOtCompesation']);
    Route::put('approve/overtimecompesations/update/{id}', [ApproveController::class, 'updateOTCompestion']);
    // change dayoff
    Route::get('approve/changedayoffs', [ApproveController::class, 'getChangeDayoff']);
    Route::get('approve/changedayoffs/edit/{id}', [ApproveController::class, 'editChangeDayOff']);
    Route::put('approve/changedayoffs/update/{id}', [ApproveController::class, 'updateChangeDayoff']);
    // leaveout
    Route::get('approve/leaveouts', [ApproveController::class, 'getLeaveout']);
    Route::get('approve/leaveouts/edit/{id}', [ApproveController::class, 'editLeaveout']);
    Route::put('approve/leaveouts/update/{id}', [ApproveController::class, 'updateLeaveout']);

    // attendance
    Route::get('checkin', [CheckinController::class, 'checkin']);
    Route::get('checkout', [CheckinController::class, 'checkout']);
    Route::get('late', [CheckinController::class, 'late']);
    // Route::get('overtime', [CheckinController::class,'overtime']);
    Route::get('absent', [CheckinController::class, 'absent']);
    Route::get('change-password', [UserController::class, 'changePassword'])->name('changePassword');
    Route::post('update-password', [UserController::class, 'updatePassword'])->name('updatePassword');
    // admin reset for user
    Route::get('user/change-password', [UserController::class, 'changeUserPassword'])->name('changePassword');
    Route::post('user/update-password', [UserController::class, 'updateUserPassword'])->name('updatePassword');

    // overtime
    Route::get('overtimes', [OvertimeController::class, 'index']);
    Route::get('overtimes/componet', [OvertimeController::class, 'getComponent']);
    Route::post('overtimes/store', [OvertimeController::class, 'store']);
    Route::delete('overtimes/delete/{id}', [OvertimeController::class, 'destroy']);
    Route::get('overtimes/edit/{id}', [OvertimeController::class, 'edit']);
    Route::post('overtimes/update/{id}', [OvertimeController::class, 'update']);
    // send overtime to account
    Route::post('overtimes/updates', [OvertimeController::class, 'sendtoAccount']);
    // Route::resource('attedance', 'AttendanceController');
    Route::get('salary', [SalaryController::class, 'index'])->name('salary');
    Route::get('salary/create', [SalaryController::class, 'create'])->name('salary/create');
    Route::post('salary/store', [SalaryController::class, 'store']);
    Route::delete('salary/delete/{id}', [SalaryController::class, 'destroy']);
    Route::get('salary/edit/{id}', [SalaryController::class, 'edit']);
    Route::post('salary/update/{id}', [SalaryController::class, 'update']);
    // Route::get('attedance_filter', 'AttendanceController@filter')->name('attedance.filter');
    // Route::post('attedance_download', 'AttendanceController@download')->name('attedance.download');
    //  structure type
    Route::get('structuretype', [StructuretypeController::class, 'index']);
    Route::post('structuretype/store', [StructuretypeController::class, 'store']);
    Route::delete('structuretype/delete/{id}', [StructuretypeController::class, 'destroy']);
    Route::get('structuretype/edit/{id}', [StructuretypeController::class, 'edit']);
    Route::put('structuretype/update/{id}', [StructuretypeController::class, 'update']);

    // structur

    Route::get('structure', [StructureController::class, 'index']);
    //  Route::get('structure/componet',[StructureController::class,'getComponent'] );
    Route::post('structure/store', [StructureController::class, 'store']);
    Route::delete('structure/delete/{id}', [StructureController::class, 'destroy']);
    Route::get('structure/edit/{id}', [StructureController::class, 'edit']);
    Route::put('structure/update/{id}', [StructureController::class, 'update']);

    // contract
    Route::get('contract', [ContractController::class, 'index']);
    Route::get('contract/componet', [ContractController::class, 'getComponent']);
    Route::post('contract/store', [ContractController::class, 'store']);
    Route::delete('contract/delete/{id}', [ContractController::class, 'destroy']);
    Route::get('contract/edit/{id}', [ContractController::class, 'edit']);
    Route::put('contract/update/{id}', [ContractController::class, 'update']);
    // payslip for account
    Route::get('payslip', [PayslipController::class, 'index'])->name('salary');
    Route::get('payslip/create', [PayslipController::class, 'create'])->name('salary/create');
    // Route::post('payslip/store', [PayslipController::class, 'store']);
    Route::post('payslip/store/{id}', [PayslipController::class, 'addPayslip']);
    Route::delete('payslip/delete/{id}', [PayslipController::class, 'destroy']);
    Route::get('payslip/edit/{id}', [PayslipController::class, 'edit']);
    Route::put('payslip/update/{id}', [PayslipController::class, 'updatePayslip']);
    Route::get('report/accountant/overtime', [PayslipController::class, 'overtimeView']);
    Route::get('report/accountant/overtime/{startDate}/{endDate}', [PayslipController::class, 'overtimeReport'])->name('a');
    Route::get('report/accountant/attendances', [PayslipController::class, 'attendanceView']);
    Route::get('report/accountant/attendances/get/{startDate}/{endDate}', [PayslipController::class, 'attendaceReport']);
    Route::get('report/accountant/employees', [PayslipController::class, 'employeeView']);

    // counter
    Route::get('counter', [CounterController::class, 'index']);
    Route::post('workdays/{date}', [WorkdayController::class, 'updateTime']);
});
