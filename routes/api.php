<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\UploadControler;
use App\Http\Controllers\TestController;

/*h
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register',[
    EmployeeController::class,'register'
]);
Route::post('login',[
    EmployeeController::class,'login'
]);
// Route::post('uploads/public',[
//     UploadControler::class,'upload'
// ]);
// Route::get('location',[
//     EmployeeController::class,'getLocation'
// ]);
Route::post('locations',[
    EmployeeController::class,'userlocation'
]);
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('me/profile',[
        EmployeeController::class,'getprofile'
    ]);
    Route::get('me/profile/check/{date}',[
        EmployeeController::class,'checkProfile'
    ]);
    Route::post('me/profile/update',[
        EmployeeController::class,'updateprofile'
    ]);

    Route::post('employee/uploads',[
        EmployeeController::class,'employeeUploads'
    ]);
    Route::put('me/profile/change-password',[
        EmployeeController::class,'changepassword'
    ]);
    Route::post('me/checkins/add',[
        EmployeeController::class,'checkin'
    ]);
    Route::put('me/checkouts/edit/{id}',[
        EmployeeController::class,'usercheckout'
    ]);
    // reminder
    Route::post('me/reminders',[
        EmployeeController::class,'addMyReminder'
    ]);
   
    // mark absent

    Route::get('me/leavetypes',[
        EmployeeController::class,'leavetype'
    ]);
    Route::get('subtypes&leave_type_id={id}',[
        EmployeeController::class,'subleavetype'
    ]);
    Route::post('me/leaves/add',[
        EmployeeController::class,'addleave'
    ]);
    Route::get('leaves/chief',[
        EmployeeController::class,'getleaveChief'
    ]);
    Route::put('leaves/chief/edit/{id}',[
        EmployeeController::class,'editLeaveChief'
    ]);
    Route::put('me/leaves/edit/{id}',[
        EmployeeController::class,'editleave'
    ]);
    Route::delete('me/leaves/delete/{id}',[
        EmployeeController::class,'deleteLeave'
    ]);
    Route::get('me/leaves',[
        EmployeeController::class,'getleave'
    ]);
    Route::get('notifications',[
        EmployeeController::class,'notification'
    ]); 
    Route::post('notification/update/fcm',[
        EmployeeController::class,'editFcmUser'
    ]); 
    Route::get('me/attendances',[
        EmployeeController::class,'getcheckinlist'
    ]);

    Route::get('me/schedules',[
        EmployeeController::class,'getSchedule'
    ]);
    // get user By department for manager department
    Route::get('chief/employees/departments',[
        EmployeeController::class,'getUserByDepartment'
    ]);
    Route::get('chief/employees/departments/all',[
        EmployeeController::class,'getAllUserByDepartment'
    ]);
    // overtime for manager of department
    Route::get('chief/overtimes/departments',[
        EmployeeController::class,'getOvertime'
    ]);
    Route::post('chief/overtimes/add',[
        EmployeeController::class,'requestOvertime'
    ]);
    
    Route::put('chief/overtimes/edit/{id}',[
        EmployeeController::class,'editOvertime'
    ]);
    Route::delete('chief/overtimes/delete/{id}',[
        EmployeeController::class,'deleteOvertime'
    ]);
    // user get overtime
    Route::get('me/overtimes',[
        EmployeeController::class,'getMyOvertime'
    ]);
    Route::put('me/overtimes/edit/{id}',[
        EmployeeController::class,'editOvertimeStatus'
    ]);
    
    // Route::resource('product',ProductController::class);
    // payslip
    Route::get('me/payslips',[
        EmployeeController::class,'getMypayslip'
    ]);
    Route::get('me/counters',[
        EmployeeController::class,'getcounter'
    ]);
    // overtime compesation
    // category
    Route::get('me/compesations',[
        EmployeeController::class,'getOvertimeCompesation'
    ]);
    Route::post('me/compesations/add',[
        EmployeeController::class,'addOvertimeCompesation'
    ]);
    Route::put('me/compesations/edit/{id}',[
        EmployeeController::class,'editOvertimeCompesation'
    ]);
    Route::delete('me/compesations/delete/{id}',[
        EmployeeController::class,'deleteOvertimeCompastion'
    ]);
    // change day off
    Route::get('me/dayoffs',[
        EmployeeController::class,'getChangeDayoff'
    ]);
    Route::get('me/ph',[
        EmployeeController::class,'getPH'
    ]);
    Route::get('me/workdays',[
        EmployeeController::class,'getWorkday'
    ]);
    Route::post('me/dayoffs/add',[
        EmployeeController::class,'addChangeholiday'
    ]);
    Route::put('me/dayoffs/edit/{id}',[
        EmployeeController::class,'editChangeholiday'
    ]);
    Route::delete('me/dayoffs/delete/{id}',[
        EmployeeController::class,'deleteChangedayoff'
    ]);
    // leaveout
    Route::get('me/leaveouts',[
        EmployeeController::class,'getLeaveout'
    ]);
    Route::post('me/leaveouts/add',[
        EmployeeController::class,'addLeaveout'
    ]);
    Route::put('me/leaveouts/edit/{id}',[
        EmployeeController::class,'editLeaveout'
    ]);
    Route::delete('me/leaveouts/delete/{id}',[
        EmployeeController::class,'deleteLeaveout'
    ]);
    // chief leaveouts
    Route::get('leaveouts/chief',[
        EmployeeController::class,'getleaveOutChief'
    ]);
    Route::put('leaveouts/chief/edit/{id}',[
        EmployeeController::class,'editLeaveOutChief'
    ]);
    // security leaveout
    Route::get('leaveouts/security',[
        EmployeeController::class,'getleaveOutSecurity'
    ]);
    Route::put('leaveouts/security/edit/{id}',[
        EmployeeController::class,'editLeaveOutSecurity'
    ]);
    // cal time
    Route::get('times',[
        EmployeeController::class,'calculateTime'
    ]);
    // dayoff chief
    Route::get('dayoffs/chief',[
        EmployeeController::class,'getDayoffChief'
    ]);
    Route::put('dayoffs/chief/edit/{id}',[
        EmployeeController::class,'editDayoffChief'
    ]);
});
//
// admin login

Route::post('admin/login',[
    AdminController::class,'login'
]);
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('qr',[
        AdminController::class,'getQr'
    ]);
    // location
    Route::get('locations',[
        AdminController::class,'getLocation'
    ]);
    Route::get('locations/all',[
        AdminController::class,'getAllLocation'
    ]);
    Route::post('locations/add',[
        AdminController::class,'addLocation'
    ]);
    Route::put('locations/edit/{id}',[
        AdminController::class,'editLocation'
    ]);
    Route::delete('locations/delete/{id}',[
        AdminController::class,'deleteLocation'
    ]);
    // working day
    Route::get('workdays',[
        AdminController::class,'getWorkday'
    ]);
    Route::get('workdays/all',[
        AdminController::class,'getAllWorkday'
    ]);
    Route::post('workdays/add',[
        AdminController::class,'addWorkday'
    ]);
    Route::put('workdays/edit/{id}',[
        AdminController::class,'editWorkday'
    ]);
    Route::delete('workdays/delete/{id}',[
        AdminController::class,'deleteWorkday'
    ]);
    // group department
    Route::get('groups',[
        AdminController::class,'getGroup'
    ]);
    Route::post('groups/add',[
        AdminController::class,'addGroup'
    ]);
    Route::put('groups/edit/{id}',[
        AdminController::class,'editGroup'
    ]);
    Route::delete('groups/delete/{id}',[
        AdminController::class,'deleteGroup'
    ]);
    // departmentBy grop
    Route::get('departments&group_id={id}',[
        AdminController::class,'getDepartmentBygroup'
    ]);
    // department
    Route::get('departments',[
        AdminController::class,'getDepartment'
    ]);
    Route::get('departments/all',[
        AdminController::class,'getAllDepartment'
    ]);
    Route::post('departments/add',[
        AdminController::class,'addDepartment'
    ]);
    Route::put('departments/edit/{id}',[
        AdminController::class,'editDepartment'
    ]);
    Route::delete('departments/delete/{id}',[
        AdminController::class,'deleteDepartment'
    ]);
    //position
    Route::get('positions',[
        AdminController::class,'getPosition'
    ]);
    Route::get('positions/all',[
        AdminController::class,'getAllPosition'
    ]);
    Route::post('positions/add',[
        AdminController::class,'addPosition'
    ]);
    Route::put('positions/edit/{id}',[
        AdminController::class,'editPosition'
    ]);
    Route::delete('positions/delete/{id}',[
        AdminController::class,'deletePosition'
    ]);
    // timetable
    Route::get('timetables',[
        AdminController::class,'getTimetable'
    ]);
    Route::get('timetables/all',[
        AdminController::class,'getAllTimetable'
    ]);
    Route::post('timetables/add',[
        AdminController::class,'addTimetable'
    ]);
    Route::put('timetables/edit/{id}',[
        AdminController::class,'editTimetable'
    ]);
    Route::delete('timetablse/delete/{id}',[
        AdminController::class,'deleteTimetable'
    ]);
    //employee
    Route::get('roles',[
        AdminController::class,'getRole'
    ]);
    Route::get('employees/attendance',[
        AdminController::class,'getAttendanceEmployee'
    ]);
    Route::get('employees',[
        AdminController::class,'getEmployee'
    ]);
    Route::get('employees/list',[
        AdminController::class,'getEmployeeList'
    ]);
    // employee detail
    Route::get('employees&employee_id={id}',[
        AdminController::class,'getEmployeeDetail'
    ]);
    Route::get('employees/all',[
        AdminController::class,'getAllEmployee'
    ]);
    Route::put('employees/reset-password/{id}',[
        AdminController::class,'resetPasswordByAdmin'
    ]);
    // Route::put('admin/reset-password',[
    //     AdminController::class,'resetAdminPassword'
    // ]);
    Route::put('admins/reset-password',[
        AdminController::class,'resetPassword'
    ]);
    Route::get('attendances',[
        AdminController::class,'getAttendance'
    ]);
    Route::post('checkins/add',[
        AdminController::class,'checkin'
    ]);
    Route::delete('checkins/delete/{id}',[
        AdminController::class,'deleteCheckin'
    ]);
    // mark absent
    Route::post('absents/add',[
        AdminController::class,'markAbsent'
    ]);
    //
    Route::put('checkouts/edit/{id}',[
        AdminController::class,'checkout'
    ]);
    Route::post('employees/add',[
        AdminController::class,'addemployee'
    ]);
    Route::put('employees/edit/{id}',[
        AdminController::class,'editemployee'
    ]);
    Route::delete('employees/delete/{id}',[
        AdminController::class,'deleteEmployee'
    ]);
    Route::get('employees&department_id={id}',[
        AdminController::class,'getEmployeeByDepartment'
    ]);
    Route::get('employees/search={query}',[
        AdminController::class,'searchEmployee'
    ]);
    // employee schedule
    Route::get('schedules',[
        AdminController::class,'getSchedule'
    ]);
    Route::post('schedules/add',[
        AdminController::class,'addSchedule'
    ]);
    Route::put('schedules/edit/{id}',[
        AdminController::class,'editSchedule'
    ]);
    Route::delete('schedules/delete/{id}',[
        AdminController::class,'deleteSchedule'
    ]);
    // employee leave
    Route::get('leaves',[
        AdminController::class,'getleave'
    ]);
    Route::post('leaves/add',[
        AdminController::class,'addLeave'
    ]);
    Route::put('leaves/edit/{id}',[
        AdminController::class,'editLeave'
    ]);
    Route::delete('leaves/delete/{id}',[
        AdminController::class,'deleteLeave'
    ]);
    // leaveout
    Route::get('leaveouts',[
        AdminController::class,'getleaveOut'
    ]);
    // leavetype
    Route::get('leavetypes',[
        AdminController::class,'getLeaveType'
    ]);
    Route::get('leavetypes/all',[
        AdminController::class,'getAllLeaveType'
    ]);
    Route::post('leavetypes/add',[
        AdminController::class,'addLeaveType'
    ]);
    Route::put('leavetypes/edit/{id}',[
        AdminController::class,'editLeaveType'
    ]);
    Route::delete('leavetypes/delete/{id}',[
        AdminController::class,'deleteLeaveType'
    ]);
    // notification
    Route::get('notifications',[
        AdminController::class,'getNotification'
    ]);
    Route::post('notifications/add',[
        AdminController::class,'addNotification'
    ]);
    Route::put('notifications/edit/{id}',[
        AdminController::class,'editNotification'
    ]);
    Route::delete('notifications/delete/{id}',[
        AdminController::class,'deleteNotification'
    ]);
    // system report
    Route::get('reports',[
        AdminController::class,'report'
    ]);
    // upload img
    Route::post('uploads',[
        AdminController::class,'upload'
    ]);
    // holiday
    Route::post('holidays/add',[
        AdminController::class,'addHoliday'
    ]);

    Route::put('holidays/edit/{id}',[
        AdminController::class,'editHoliday'
    ]);
    Route::delete('holidays/delete/{id}',[
        AdminController::class,'deleteHoliday'
    ]);
    // system report
    Route::get('holidays',[
        AdminController::class,'getHoliday'
    ]);
    Route::post('holidays/add/alert/{id}',[
        AdminController::class,'alertHoliday'
    ]);
    // overtime
    Route::post('overtimes/add',[
        AdminController::class,'requetOvertime'
    ]);

    Route::put('overtimes/edit/{id}',[
        AdminController::class,'editOvertime'
    ]);
    Route::delete('overtimes/delete/{id}',[
        AdminController::class,'deleteOvertime'
    ]);
    // system report
    Route::get('overtimes',[
        AdminController::class,'getOvertime'
    ]);
    Route::post('overtime/add/alert/{id}',[
        AdminController::class,'alertHoliday'
    ]);
    // overtime compesation
    Route::get('overtimes/compesations',[
        AdminController::class,'getOTCompesation'
    ]);
    Route::put('overtimes/compesations/edit/{id}',[
        AdminController::class,'editOTCompesation'
    ]);
    // dayoff
    Route::get('dayoffs',[
        AdminController::class,'getChangeDayoff'
    ]);
    Route::put('dayoffs/edit/{id}',[
        AdminController::class,'editChangeDayOff'
    ]);
    // Route::post('products/add',[
    //     AdminController::class,'product'
    // ]);
    Route::get('reports/attendance',[
        AdminController::class,'attendance'
    ]);
    Route::get('reports/leave',[
        AdminController::class,'leave'
    ]);
    Route::get('reports/overtime',[
        AdminController::class,'overtime'
    ]);
    // structure type
    Route::get('structures',[
        AdminController::class,'getStructure'
    ]);
    Route::get('structures/all',[
        AdminController::class,'getAllStructure'
    ]);
     Route::post('structures/add',[
        AdminController::class,'addStructure'
    ]);

    Route::put('structures/edit/{id}',[
        AdminController::class,'editStructure'
    ]);
    Route::delete('structures/delete/{id}',[
        AdminController::class,'deleteStructure'
    ]);
    // structure
    Route::get('structuretypes',[
        AdminController::class,'getStructuretype'
    ]);
     Route::post('structuretypes/add',[
        AdminController::class,'addStructuretype'
    ]);

    Route::put('structuretypes/edit/{id}',[
        AdminController::class,'editStructuretype'
    ]);
    Route::delete('structuretypes/delete/{id}',[
        AdminController::class,'deleteStructuretype'
    ]);
    // contract
    Route::get('contracts',[
        AdminController::class,'getContract'
    ]);
    Route::get('contracts/all',[
        AdminController::class,'getAllContract'
    ]);
     Route::post('contracts/add',[
        AdminController::class,'addContract'
    ]);

    Route::put('contracts/edit/{id}',[
        AdminController::class,'editContract'
    ]);
    Route::delete('contracts/delete/{id}',[
        AdminController::class,'deleteContract'
    ]);
    // system report
    
    // payslip
    Route::get('payslips',[
        AdminController::class,'getPayslip'
    ]);
    Route::post('payslips/add',[
        AdminController::class,'addPayslip'
    ]);

    Route::put('payslips/edit/{id}',[
        AdminController::class,'editPayslip'
    ]);
    Route::delete('payslips/delete/{id}',[
        AdminController::class,'deleteContract'
    ]);
    // 
    Route::get('times',[
        AdminController::class,'tineNow'
    ]);
    // user counter
    Route::get('counters',[
        AdminController::class,'getCounter'
    ]);
    Route::get('dashboard',[
        AdminController::class,'getdashboard'
    ]);
});
Route::post('test',[TestController::class, 'list']);



