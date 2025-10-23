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
use App\Http\Controllers\SchoolFeeController;
use Illuminate\Support\Facades\Broadcast;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushSubscriptionController;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


// ADMIN DASHBOARD ROUTES

// Error routes
Route::view('/error/not_authorized', 'errors.401_not_authorized')->name('error.not_authorized');
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

// Account Settings (Admin)
Route::get('/accountSettings', [AdminController::class, 'accountSettings'])->name('account.settings');
Route::put('/accountSettings/{id}', [AdminController::class, 'updateAdmin'])->name('admin.update');

// Account Settings (Teacher)
Route::get('/teacher/accountSettings', [TeacherController::class, 'accountSettings'])->name('teacher.account.settings');
Route::put('/teacher/accountSettings/{id}', [TeacherController::class, 'updateTeacher'])->name('teacher.update');

// Account Settings (Parent)
Route::get('/parent/accountSettings', [ParentController::class, 'accountSettings'])->name('parent.account.settings');
Route::put('/parent/accountSettings/{id}', [ParentController::class, 'updateParent'])->name('parent.update');


// Route::post('/updateAdminAccount/{id}', [AdminController::class, 'updateAdmin'])->name('update.admin');

Route::get('/showAllTeachers', [AdminController::class, 'showAllTeachers'])->name('show.teachers');

Route::post('/registerTeacher', [AdminController::class, 'registerTeacher'])->name('register.teacher');

Route::get('/editTeacher/{id}', [AdminController::class, 'editTeacher'])->name('edit.teacher');

Route::post('/updateTeacher/{id}', [AdminController::class, 'updateTeacher'])->name('update.teacher');

Route::delete('/deleteTeacher/{id}', [AdminController::class, 'deleteTeacher'])->name('delete.teacher');

Route::get('/teacherInfo/{id}', [AdminController::class, 'teacherInfo'])->name('teacher.info');

Route::post('/teacher_reassignment', [AdminController::class, 'reassignment'])->name('teacher.reassignment');



// Students Management (on ADMIN dashboard)
Route::get('/student-management', [StudentController::class, 'studentManagement'])->name('student.management');

Route::post('/addStudent', [StudentController::class, 'store'])->name('store.student');

Route::get('students/import', [StudentController::class, 'showImportForm'])->name('students.showImportForm');
Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
Route::get('/students/download-template', [StudentController::class, 'downloadTemplate'])
    ->name('students.downloadTemplate');

Route::get('/studentEnrollment', [StudentController::class, 'show'])->name('show.students');

Route::post('/assignStudentClass', [StudentController::class, 'assignClass'])->name('assign.student.class');

Route::get('/students/search/not-enrolled', [StudentController::class, 'searchNotEnrolled'])->name('students.search.not.enrolled');

Route::get('/editStudent/{id}', [StudentController::class, 'edit'])->name('edit.student');

Route::post('/updateStudent/{id}', [StudentController::class, 'update'])->name('update.student');

Route::delete('/unenrollStudent/{id}', [StudentController::class, 'unenroll'])->name('unenroll.student');

Route::get('/student-info/{id}', [StudentController::class, 'showStudentInfo'])->name('student.info');

Route::delete('/deleteStudent/{id}', [StudentController::class, 'delete'])->name('delete.student');

Route::delete('/deleteStudents/bulk', [StudentController::class, 'bulkDelete'])->name('students.bulkDelete');

// View students eligible for promotion
Route::get('/promote-students', [StudentController::class, 'showPromotionView'])->name('students.promote.view');

// Handle the promotion
Route::post('/promote-students', [StudentController::class, 'promoteStudents'])->name('students.promote');



// ID Management (on ADMIN dashboard)
Route::get('/students/{id}/generate-id', [IdController::class, 'generateID'])->name('students.generateID');

Route::get('/students/{id}/preview-id', [IdController::class, 'previewID'])->name('students.previewID');


// Class Management (on ADMIN dashboard)
Route::get('/classes', [ClassController::class, 'allClasses'])->name('all.classes');

Route::get('/classes/{grade_level}/{section}', [ClassController::class, 'showClass'])->name('classes.showClass');

Route::get('/classes/{grade_level}/{section}/masterList', [ClassController::class, 'masterList'])->name('classes.masterList');

Route::get('/classes/{grade_level}/{section}/subjects', [ClassController::class, 'subjects'])->name('classes.subjects');

Route::get('/classes/{grade_level}/{section}/{subject}/grades', [ClassController::class, 'grades'])->name('classes.grades');


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
});

Route::get('/users/search', [AnnouncementController::class, 'searchUser'])->name('search.user')->middleware('auth');

Route::get('/announcement/redirect/{id}', function ($id) {
    if (Auth::check()) {
        // Logged in — send to dashboard
        return redirect()->route('home', ['announcement_id' => $id]);
    } else {
        // Not logged in — send to login page
        return redirect()->route('login')->with('redirect_announcement', $id);
    }
})->name('announcement.redirect');

Route::post('/announcements/upload-image', [AnnouncementController::class, 'uploadImage'])
    ->name('announcements.uploadImage');

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

// for auto-mark absent
Route::post('/attendance/auto-mark-absent', [AttendanceController::class, 'autoMarkAbsent'])
    ->name('attendance.autoMarkAbsent');

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

// Delete subject from a class
Route::delete(
    '/teacher/class/{grade_level}/{section}/subjects/{subject_id}/delete',
    [TeacherController::class, 'deleteSubject']
)->name('teacher.subjects.delete');


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
Route::get('payments/', [SchoolFeeController::class, 'index'])
    ->name('admin.school-fees.index');

Route::post('payments/create', [SchoolFeeController::class, 'create'])
    ->name('admin.payments.create');

Route::delete('payments/{payment}', [SchoolFeeController::class, 'destroy'])
    ->name('admin.payments.destroy');

Route::put('/admin/payments/{id}/add', [SchoolFeeController::class, 'addPayment'])->name('admin.payments.add');

Route::get('/admin/payments/{paymentName}/history', [SchoolFeeController::class, 'history'])->name('admin.payments.history');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/payment-requests', [SchoolFeeController::class, 'viewRequests'])->name('admin.payment.requests');
    Route::post('/payment-requests/{id}/approve', [SchoolFeeController::class, 'approveRequest'])->name('admin.payment.requests.approve');
    Route::post('/payment-requests/{id}/deny', [SchoolFeeController::class, 'denyRequest'])->name('admin.payment.requests.deny');
});



Route::get('payments/show/{paymentName}', [SchoolFeeController::class, 'show'])
    ->name('admin.school-fees.show');

Route::delete('/payments/history/{id}', [SchoolFeeController::class, 'deleteHistory'])
    ->name('admin.payments.history.delete');



Route::post('payments/bulk-add-payment', [SchoolFeeController::class, 'bulkAddPayment'])
    ->name('admin.payments.bulkAddPayment');

Route::post('/admin/payments/{paymentName}/add-students', [SchoolFeeController::class, 'addStudents'])
    ->name('admin.payments.addStudents');

Route::get('/class-students/search/exclude-payment', [StudentController::class, 'classStudentSearchExcludePayment'])
    ->name('class-students.search.exclude-payment');


Route::post('payments/bulk-remove', [SchoolFeeController::class, 'bulkRemoveStudents'])
    ->name('admin.payments.bulkRemoveStudents');

Route::get('/class-students/search', [StudentController::class, 'classStudentSearch'])
    ->name('class-students.search');



// Payment Management (on Teacher's Dashboard and Parent's Dashboard)

// Teacher routes (consistent grade_level + section)
Route::prefix('teacher')->middleware(['auth'])->group(function () {
    Route::get('classes/{grade_level}/{section}/payments', [SchoolFeeController::class, 'index'])
        ->name('teacher.payments.index');

    Route::get('classes/{grade_level}/{section}/payments/{paymentName}', [SchoolFeeController::class, 'show'])
        ->name('teacher.payments.show');

    Route::post('classes/{grade_level}/{section}/payments', [SchoolFeeController::class, 'create'])
        ->name('teacher.payments.create');
});

// Parent routes
Route::prefix('parent')->middleware(['auth', 'role:parent'])->group(function () {
    Route::get('students/{student}/payments', [SchoolFeeController::class, 'studentPayments'])->name('parent.payments.index');
    Route::post('payments/{payment}/pay', [SchoolFeeController::class, 'pay'])->name('parent.payments.pay');
});




// PARENT DASHBOARD ROUTES
Route::middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/parent/children/{id}', function ($id) {
        $child = Student::with(['classStudents.class', 'schoolYears'])->findOrFail($id);
        return view('parent.child-profile', compact('child'));
    })->name('parent.children.show');
});

Route::get('/parent/children', [ParentController::class, 'children'])->name('parent.children.index');
Route::get('/parent/children/{id}', [ParentController::class, 'showChild'])->name('parent.children.show');
Route::get('/student/{student}/attendance/{schoolYearId}/{classId}/{year}/{month}', [AttendanceController::class, 'fetchMonth'])
    ->name('attendance.fetchMonth');


Route::get('/parent/school-fees', [ParentController::class, 'schoolFees'])->name('parent.school-fees.index');
Route::get('/parent/school-fees/{paymentName}', [ParentController::class, 'showSchoolFee'])->name('parent.school-fees.show');
Route::post('/parent/payment/{id}/add', [ParentController::class, 'addPayment'])->name('parent.addPayment');




Route::get('/parent/announcements', [ParentController::class, 'announcements'])->name('parent.announcements.index');
Route::get('/parent/sms-logs', [ParentController::class, 'smsLogs'])->name('parent.sms-logs.index');







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
