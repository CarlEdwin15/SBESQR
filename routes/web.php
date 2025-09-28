<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IdController;
use App\Http\Controllers\HomeController;
use App\Exports\TeachersExport;
use App\Exports\SF2Export;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Broadcast;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushSubscriptionController;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


// ADMIN DASHBOARD ROUTES

// Error routes
Route::view('/error/inactive', 'errors.423_inactive')->name('error.inactive');
Route::view('/error/suspended', 'errors.402_suspended')->name('error.suspended');
Route::view('/error/banned', 'errors.403_banned')->name('error.banned');

Route::post('/admin/users/bulk-update-status', [AdminController::class, 'bulkUpdateStatus'])
    ->name('admin.users.bulkUpdateStatus');

// Protected routes
Route::middleware(['auth', 'verified', \App\Http\Middleware\CheckUserStatus::class])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});


// User Management (on ADMIN dashboard)
Route::get('/userManagement', [AdminController::class, 'userManagement'])
    ->name('admin.user.management')
    ->middleware(['auth', 'verified', \App\Http\Middleware\CheckUserStatus::class]);

// User Management - create new user
Route::post('/userManagement/create', [AdminController::class, 'createUser'])
    ->name('admin.user.create')
    ->middleware(['auth', 'verified', \App\Http\Middleware\CheckUserStatus::class]);

// User Management - update user
Route::put('/userManagement/{id}/update', [AdminController::class, 'updateUser'])
    ->name('admin.user.update')
    ->middleware(['auth', 'verified', \App\Http\Middleware\CheckUserStatus::class]);

// User Management - delete user
Route::delete('/userManagement/{id}/delete', [AdminController::class, 'deleteUser'])
    ->name('admin.user.delete')
    ->middleware(['auth', 'verified', \App\Http\Middleware\CheckUserStatus::class]);

// For searching students thru tom selection - search students (AJAX)
Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');

// View user info
Route::get('/userInfo/{id}', [AdminController::class, 'userInfo'])->name('admin.user.info');


// Fetch user status (for AJAX polling)
Route::get('/admin/user-status-refresh', function () {
    $users = User::select('id', 'sign_in_at', 'last_sign_in_at')
        ->get()
        ->mapWithKeys(fn($user) => [
            $user->id => [
                'is_online' => $user->is_online,
                'last_seen' => $user->last_seen ?? 'N/A',
            ],
        ]);

    return response()->json($users);
})->middleware(['auth', 'verified']);

// Update user status
Route::post('/admin/users/{user}/status', [AdminController::class, 'updateUserStatus'])
    ->name('admin.users.updateStatus')
    ->middleware(['auth', 'verified']);


// Google login
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Facebook login
// Route::get('/auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
// Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

// User Account Settings (on ADMIN dashboard)
Route::get('/accountSettings', [AdminController::class, 'accountSettings'])->name('account.settings');

Route::post('/updateAdminAccount/{id}', [AdminController::class, 'updateAdmin'])->name('update.admin');

Route::get('/showAllTeachers', [AdminController::class, 'showAllTeachers'])->name('show.teachers');

Route::post('/registerTeacher', [AdminController::class, 'registerTeacher'])->name('register.teacher');

Route::get('/editTeacher/{id}', [AdminController::class, 'editTeacher'])->name('edit.teacher');

Route::post('/updateTeacher/{id}', [AdminController::class, 'updateTeacher'])->name('update.teacher');

Route::delete('/deleteTeacher/{id}', [AdminController::class, 'deleteTeacher'])->name('delete.teacher');

Route::get('/teacherInfo/{id}', [AdminController::class, 'teacherInfo'])->name('teacher.info');

Route::post('/teacher_reassignment', [AdminController::class, 'reassignment'])->name('teacher.reassignment');



// Students Management (on ADMIN dashboard)
Route::get('/addStudent', [StudentController::class, 'create'])->name('add.student');

Route::post('/addStudent', [StudentController::class, 'store'])->name('store.student');

Route::get('/showAllStudents', [StudentController::class, 'show'])->name('show.students');

Route::get('/editStudent/{id}', [StudentController::class, 'edit'])->name('edit.student');

Route::post('/updateStudent/{id}', [StudentController::class, 'update'])->name('update.student');

Route::delete('/unenrollStudent/{id}', [StudentController::class, 'unenroll'])->name('unenroll.student');

Route::get('/student-info/{id}', [StudentController::class, 'showStudentInfo'])->name('student.info');

// View students eligible for promotion
Route::get('/promote-students', [StudentController::class, 'showPromotionView'])->name('students.promote.view');

// Handle the promotion
Route::post('/promote-students', [StudentController::class, 'promoteStudents'])->name('students.promote');



// ID Management (on ADMIN dashboard)
Route::get('/students/{id}/generate-id', [IdController::class, 'generateID'])->name('students.generateID');

Route::get('/students/{id}/download-id', [IdController::class, 'downloadID'])->name('students.downloadID');


// Class Management (on ADMIN dashboard)
Route::get('/classes', [ClassController::class, 'allClasses'])->name('all.classes');

Route::get('/classes/{grade_level}/{section}', [ClassController::class, 'showClass'])->name('classes.showClass');

Route::get('/classes/{grade_level}/{section}/masterList', [ClassController::class, 'masterList'])->name('classes.masterList');

Route::get('/classes/{grade_level}/{section}/export-master-list', [ClassController::class, 'exportMasterList'])->name('classes.exportMasterList');


// Schedule Management (on ADMIN dashboard)
Route::get('/classes/{grade_level}/{section}/schedule', [ScheduleController::class, 'displaySchedule'])->name('classes.schedule.index');

Route::post('/classes/{grade_level}/{section}/add-schedule', [ScheduleController::class, 'addSchedule'])->name('classes.addSchedule');

Route::post('classes/{grade_level}/{section}/edit-schedule', [ScheduleController::class, 'editSchedule'])->name('classes.editSchedule');

Route::delete('classes/{grade_level}/{section}/delete-schedule/{schedule_id}', [ScheduleController::class, 'deleteSchedule'])->name('classes.deleteSchedule');


// Attendance Management (on ADMIN dashboard)
Route::get('attendance-records/{grade_level}/{section}', [AttendanceController::class, 'attendanceRecords'])->name('classes.attendance.records');

Route::get('attendance-history/{grade_level}/{section}/{date?}/{schedule_id?}', [AttendanceController::class, 'attendanceHistory'])->name('classes.attendance.history');


// Announcement Management (on ADMIN Dashboard)
Route::middleware('auth')->prefix('announcements')->name('announcements.')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('index');
    Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
    Route::post('/', [AnnouncementController::class, 'store'])->name('store');
    Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
    Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
    Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');
    Route::post('upload-image', [AnnouncementController::class, 'uploadImage'])->name('uploadImage');
});

// Show ajax notification
Route::get('/announcements/{id}/show-ajax', [AnnouncementController::class, 'showAjax'])
    ->name('announcements.showAjax');

// Announcement in landing page
Route::get('/', function () {
    $announcements = Announcement::orderBy('date_published', 'desc')
        ->take(99)
        ->get()
        ->filter(function ($announcement) {
            return $announcement->getStatus() === 'active';
        });

    return view('welcome', compact('announcements'));
})->name('welcome');

// Push subscription for Notifications
Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])
    ->name('push.subscribe')->middleware('auth');
Route::delete('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])
    ->name('push.unsubscribe')->middleware('auth');






// TEACHER DASHBOARD ROUTES

//List of Teacher's Students (on teacher Dashboard)
Route::get('myStudents', [TeacherController::class, 'myStudents'])->name('teacher.my.students');

Route::get('myClasses', [TeacherController::class, 'myClasses'])->name('teacher.myClasses');

Route::get('myClass/{grade_level}/{section}', [TeacherController::class, 'myClass'])->name('teacher.myClass');

Route::get('mySchedule/{grade_level}/{section}', [TeacherController::class, 'mySchedule'])->name('teacher.mySchedule');

Route::get('myClassMasterList/{grade_level}/{section}', [TeacherController::class, 'myClassMasterList'])->name('teacher.myClassMasterList');


// Attendance Management (Teacher's Dashboard)
Route::get('myAttendanceRecord/{grade_level}/{section}', [TeacherController::class, 'myAttendanceRecord'])->name('teacher.myAttendanceRecord');

Route::get('attendanceHistory/{grade_level}/{section}/{date?}/{schedule_id?}', [TeacherController::class, 'attendanceHistory'])->name('teacher.attendanceHistory');

Route::post('submitAttendance', [TeacherController::class, 'submitAttendance'])->name('teacher.submitAttendance');

Route::get('/teacher/attendance/{grade}/{section}/scan/{date?}/{schedule_id?}', [TeacherController::class, 'showScanner'])
    ->name('teacher.scanAttendance');

Route::post('/teacher/attendance/qr-mark', [TeacherController::class, 'markAttendanceFromQR'])
    ->name('teacher.markAttendanceFromQR');

Route::post('/teacher/manual-attendance', [TeacherController::class, 'markManualAttendance'])->name('teacher.markManualAttendance');

// Export SF2 (Teacher's Dashboard)
Route::get('/teacher-export-attendance', function () {
    $controller = app(AttendanceController::class);
    $data = $controller->getAttendanceExportData();

    $schoolYear = $data['selectedYear'] ?? 'UnknownYear';
    $gradeLevel = $data['class']->formatted_grade_level ?? 'UnknownGrade';
    $section = $data['class']->section ?? 'UnknownSection';
    $data['adviserName'] = Auth::user()->full_name;
    $fileName = "SBESQR_SF2_{$schoolYear}_{$gradeLevel} - {$section}.xlsx";

    return Excel::download(new SF2Export($data), $fileName);
})->name('export.sf2');


// Subject Management (on Teacher's Dashboard)
Route::get('/teacher/subjects/{grade_level}/{section}', [TeacherController::class, 'myClassSubject'])->name('teacher.myClassSubject');

Route::post('/teacher/class/{grade_level}/{section}/subjects/create', [TeacherController::class, 'createSubject'])
    ->name('teacher.subjects.create');

Route::get(
    '/teacher/class/{grade_level}/{section}/subjects/{subject_id}/view',
    [TeacherController::class, 'viewSubject']
)->name('teacher.subjects.view');


// Export Student Report Card grades in the student info view
Route::get(
    '/teacher/student/{student_id}/report_card/export',
    [TeacherController::class, 'studentReportCard']
)->name('teacher.student.card');

// Quarterly grades export in the viewSubject view
Route::get(
    '/teacher/class/{grade_level}/{section}/subjects/{subject_id}/export',
    [TeacherController::class, 'exportQuarterlyGrades']
)->name('teacher.subjects.export');


Route::post(
    '/teacher/class/{grade_level}/{section}/subjects/{subject_id}/save-grades',
    [TeacherController::class, 'saveGrades']
)->name('teacher.subjects.saveGrades');

Route::delete(
    '/teacher/subjects/{grade_level}/{section}/{subject_id}/grades/{student_id}/{quarter}',
    [TeacherController::class, 'deleteGrade']
)
    ->name('teacher.subjects.deleteGrade');


//List of Student's Info (on teacher Dashboard)
Route::get('/studentInfo/{id}', [TeacherController::class, 'studentInfo'])->name('teacher.student.info');
Route::get('/editStudentInfo/{id}', [TeacherController::class, 'editStudentInfo'])->name('teacher.edit.student');
Route::post('/updateStudentInfo/{id}', [TeacherController::class, 'updateStudentInfo'])->name('teacher.update.student');

// Payment Management (on Admin Dashboard)
Route::get('payments/', [PaymentController::class, 'indexAdmin'])
    ->name('admin.payments.index');

Route::post('payments/create', [PaymentController::class, 'createAdmin'])
    ->name('admin.payments.create');

Route::get('payments/show/{paymentName}', [PaymentController::class, 'showAdmin'])
    ->name('admin.payments.show');

Route::put('payments/{id}/update', [PaymentController::class, 'update'])
    ->name('teacher.payments.update');

Route::get('/class-students/search', [StudentController::class, 'classStudentSearch'])
    ->name('class-students.search');



// Payment Management (on Teacher's Dashboard and Parent's Dashboard)

// Teacher routes (consistent grade_level + section)
Route::prefix('teacher')->middleware(['auth'])->group(function () {
    Route::get('classes/{grade_level}/{section}/payments', [PaymentController::class, 'index'])
        ->name('teacher.payments.index');

    Route::get('classes/{grade_level}/{section}/payments/{paymentName}', [PaymentController::class, 'show'])
        ->name('teacher.payments.show');

    Route::post('classes/{grade_level}/{section}/payments', [PaymentController::class, 'create'])
        ->name('teacher.payments.create');
});

// Parent routes
Route::prefix('parent')->middleware(['auth', 'role:parent'])->group(function () {
    Route::get('students/{student}/payments', [PaymentController::class, 'studentPayments'])->name('parent.payments.index');
    Route::post('payments/{payment}/pay', [PaymentController::class, 'pay'])->name('parent.payments.pay');
});



// Route::get('/export-students', function () {
//     return Excel::download(new StudentsExport, 'List of students.xlsx');
// })->name('export.students');

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
