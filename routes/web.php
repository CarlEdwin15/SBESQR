<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IdController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SchoolFeeController;
use App\Http\Controllers\PushSubscriptionController;

use App\Exports\SF2Export;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\User;


// ERROR ROUTES
Route::view('/error/not_authorized', 'errors.401_not_authorized')->name('error.not_authorized');
Route::view('/error/inactive', 'errors.423_inactive')->name('error.inactive');
Route::view('/error/suspended', 'errors.402_suspended')->name('error.suspended');
Route::view('/error/banned', 'errors.403_banned')->name('error.banned');

// Clear announcement session flags
Route::post('/clear-announcement-session', function (Request $request) {
    $type = $request->input('type', 'all');

    if ($type === 'login' || $type === 'all') {
        session()->forget('show_announcements_on_login');
        session()->forget('active_announcements_on_login');
    }

    if ($type === 'manual' || $type === 'all') {
        session()->forget('manual_announcement_id');
        session()->forget('login_announcement_id');
    }

    return response()->json(['success' => true]);
})->middleware('auth');

// Clear announcement check flag after showing announcements
Route::post('/clear-announcement-check-flag', function (Request $request) {
    session()->forget('check_announcements');
    session()->forget('page_loaded');
    return response()->json(['success' => true]);
})->middleware('auth');

// Update the /get-active-announcements route
Route::get('/get-active-announcements', function (Request $request) {
    $user = Auth::user();

    if (!$user) {
        return response()->json(['announcements' => []]);
    }

    $now = now();

    // Get announcements that are active AND applicable to the user
    $announcements = Announcement::with('schoolYear', 'user')
        ->where(function ($query) use ($now) {
            // Date-based active status
            $query->where('effective_date', '<=', $now)
                ->where(function ($subQuery) use ($now) {
                    $subQuery->where('end_date', '>=', $now)
                        ->orWhereNull('end_date');
                });
        })
        ->where('status', 'active')
        ->where(function ($query) use ($user) {
            // Recipient-based filtering
            // Announcements specifically sent to this user
            $query->whereHas('recipients', function ($subQuery) use ($user) {
                $subQuery->where('user_id', $user->id);
            })
                // OR general announcements (no specific recipients)
                ->orWhereDoesntHave('recipients');
        })
        ->orderByDesc('date_published')
        ->get()
        ->map(function ($announcement) {
            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'body' => $announcement->body,
                'date_published' => $announcement->date_published?->format('M d, Y h:i A'),
                'author_name' => $announcement->author_name,
            ];
        })
        ->values();

    return response()->json(['announcements' => $announcements]);
})->middleware('auth');


// GENERAL & AUTH ROUTES
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard data routes
    Route::get('/admin/dashboard/enrollment-data', [HomeController::class, 'getEnrollmentData'])->name('admin.dashboard.enrollment-data');
    Route::get('/admin/dashboard/gender-data', [HomeController::class, 'getGenderData'])->name('admin.dashboard.gender-data');
    Route::get('/admin/dashboard/school-year-info', [HomeController::class, 'getSchoolYearInfo'])->name('admin.dashboard.school-year-info');
    Route::get('/admin/active-users', [HomeController::class, 'getActiveUsers'])
        ->name('admin.active-users');
    Route::get('/admin/dashboard/grade-sections', [HomeController::class, 'getGradeSections'])->name('admin.dashboard.grade-sections');
    Route::get('/admin/dashboard/gender-data-filtered', [HomeController::class, 'getGenderDataFiltered'])->name('admin.dashboard.gender-data-filtered');

    Route::get('/admin/payment-requests/check-dashboard', [SchoolFeeController::class, 'checkDashboardRequests'])
        ->name('admin.payment-requests.check-dashboard');
    Route::get('/admin/school-fees/notification-counts', [SchoolFeeController::class, 'getNotificationCounts'])->name('admin.school-fees.notification-counts');
    Route::get('/admin/school-fees/payment-notification-counts', [SchoolFeeController::class, 'getPaymentNotificationCounts'])->name('admin.school-fees.payment-notification-counts');
    // Add this route to your admin routes group
    Route::get('/admin/dashboard/school-fees-data', [HomeController::class, 'getSchoolFeesData'])->name('admin.dashboard.school-fees-data');
    // Main dashboard route
    Route::get('/admin/payment-requests/check-new', [SchoolFeeController::class, 'checkNewRequests'])
        ->name('admin.payment-requests.check-new');


    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Public welcome page
Route::get('/', function () {
    $announcements = Announcement::orderBy('date_published', 'desc')
        ->take(99)
        ->get()
        ->filter(fn($a) => $a->getStatus() === 'active');

    return view('welcome', compact('announcements'));
})->name('welcome');

// Announcement redirect route for notifications
Route::get('/announcement/redirect/{id}', [AnnouncementController::class, 'redirect'])
    ->name('announcement.redirect');

// ADMIN DASHBOARD ROUTES
Route::prefix('admin')
    ->middleware([
        'auth',
        'verified',
        \App\Http\Middleware\RoleMiddleware::class . ':admin',
        \App\Http\Middleware\CheckUserStatus::class
    ])
    ->group(function () {

        // User Management
        Route::get('/userManagement', [AdminController::class, 'userManagement'])->name('admin.user.management');
        Route::post('/userManagement/create', [AdminController::class, 'createUser'])->name('admin.user.create');
        Route::get('/userManagement/check-email', [AdminController::class, 'checkEmail'])->name('admin.check.email');
        Route::put('/userManagement/{id}/update', [AdminController::class, 'updateUser'])->name('admin.user.update');
        Route::delete('/userManagement/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
        Route::get('/userInfo/{id}', [AdminController::class, 'userInfo'])->name('admin.user.info');
        Route::post('/users/bulk-update-status', [AdminController::class, 'bulkUpdateStatus'])->name('admin.users.bulkUpdateStatus');
        Route::post('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.updateStatus');
        Route::get('/user-status-refresh', function () {
            $users = User::select('id', 'sign_in_at', 'last_sign_in_at')
                ->get()
                ->mapWithKeys(fn($user) => [
                    $user->id => [
                        'is_online' => $user->is_online,
                        'last_seen' => $user->last_seen ?? 'N/A',
                    ],
                ]);
            return response()->json($users);
        })->name('admin.userStatusRefresh');

        // Account Settings (Admin)
        Route::get('/accountSettings', [AdminController::class, 'accountSettings'])->name('admin.account.settings');
        Route::put('/accountSettings/{id}', [AdminController::class, 'updateAdmin'])->name('admin.update');

        // Teachers Management
        Route::get('/showAllTeachers', [AdminController::class, 'showAllTeachers'])->name('show.teachers');
        Route::post('/registerTeacher', [AdminController::class, 'registerTeacher'])->name('register.teacher');
        Route::get('/editTeacher/{id}', [AdminController::class, 'editTeacher'])->name('edit.teacher');
        Route::post('/updateTeacher/{id}', [AdminController::class, 'updateTeacher'])->name('update.teacher');
        Route::delete('/deleteTeacher/{id}', [AdminController::class, 'deleteTeacher'])->name('delete.teacher');
        Route::get('/teacherInfo/{id}', [AdminController::class, 'teacherInfo'])->name('teacher.info');
        Route::post('/teacher_reassignment', [AdminController::class, 'reassignment'])->name('teacher.reassignment');

        // Student Management
        Route::get('/student-management', [StudentController::class, 'studentManagement'])->name('student.management');
        Route::post('/addStudent', [StudentController::class, 'store'])->name('store.student');
        Route::get('/students/import', [StudentController::class, 'showImportForm'])->name('students.showImportForm');
        Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
        Route::get('/students/download-template', [StudentController::class, 'downloadTemplate'])->name('students.downloadTemplate');
        Route::get('/studentEnrollment', [StudentController::class, 'show'])->name('show.students');
        Route::post('/assignStudentClass', [StudentController::class, 'assignClass'])->name('assign.student.class');
        Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
        Route::get('/students/search/not-enrolled', [StudentController::class, 'searchNotEnrolled'])->name('students.search.not.enrolled');
        Route::get('/editStudent/{id}', [StudentController::class, 'edit'])->name('edit.student');
        Route::post('/updateStudent/{id}', [StudentController::class, 'update'])->name('update.student');
        Route::delete('/unenrollStudent/{id}', [StudentController::class, 'unenroll'])->name('unenroll.student');
        Route::post('/students/bulk-unenroll', [StudentController::class, 'bulkUnenroll'])->name('students.bulkUnenroll');
        Route::post('/students/bulk-ungraduate', [StudentController::class, 'bulkUngraduate'])->name('students.bulkUngraduate');
        Route::delete('/deleteStudent/{id}', [StudentController::class, 'delete'])->name('delete.student');
        Route::delete('/deleteStudents/bulk', [StudentController::class, 'bulkDelete'])->name('students.bulkDelete');
        Route::get('/student-info/{id}', [StudentController::class, 'showStudentInfo'])->name('student.info');
        Route::get('/promote-students', [StudentController::class, 'showPromotionView'])->name('students.promote.view');
        Route::post('/promote-students', [StudentController::class, 'promoteStudents'])->name('students.promote');
        Route::get('/admin/students/search-classes', [StudentController::class, 'searchClasses'])->name('students.search.classes');

        // ID Management
        Route::get('/students/{id}/generate-id', [IdController::class, 'generateID'])->name('students.generateID');
        Route::get('/students/{id}/preview-id', [IdController::class, 'previewID'])->name('students.previewID');
        // Add this route to your admin routes group
        Route::post('/students/bulk-print-ids', [IdController::class, 'bulkPrintIDs'])->name('students.bulkPrintIDs');

        // Class Management
        Route::get('/classes', [ClassController::class, 'allClasses'])->name('all.classes');
        Route::get('/classes/{grade_level}/{section}', [ClassController::class, 'showClass'])->name('classes.showClass');
        Route::get('/classes/{grade_level}/{section}/masterList', [ClassController::class, 'masterList'])->name('classes.masterList');
        Route::get('/classes/{grade_level}/{section}/subjects', [ClassController::class, 'subjects'])->name('classes.subjects');
        Route::get('/classes/{grade_level}/{section}/{subject}/grades', [ClassController::class, 'grades'])->name('classes.grades');

        // Schedule Management
        Route::get('/classes/{grade_level}/{section}/schedule', [ScheduleController::class, 'displaySchedule'])->name('classes.schedule.index');
        Route::post('/classes/{grade_level}/{section}/add-schedule', [ScheduleController::class, 'addSchedule'])->name('classes.addSchedule');
        Route::post('/classes/{grade_level}/{section}/edit-schedule', [ScheduleController::class, 'editSchedule'])->name('classes.editSchedule');
        Route::delete('/classes/{grade_level}/{section}/delete-schedule/{schedule_id}', [ScheduleController::class, 'deleteSchedule'])->name('classes.deleteSchedule');

        // Attendance Management
        Route::get('/attendance-records/{grade_level}/{section}', [AttendanceController::class, 'attendanceRecords'])->name('classes.attendance.records');
        Route::get('/attendance-history/{grade_level}/{section}/{date?}/{schedule_id?}', [AttendanceController::class, 'attendanceHistory'])->name('classes.attendance.history');

        // Announcements
        Route::resource('/announcements', AnnouncementController::class);
        // Route::get('/announcement/redirect/{id}', [AnnouncementController::class, 'redirect'])->name('announcement.redirect');

        // Payments
        Route::get('/payments', [SchoolFeeController::class, 'index'])->name('admin.school-fees.index');
        Route::post('/payments/create', [SchoolFeeController::class, 'create'])->name('admin.payments.create');
        Route::delete('/payments/{payment}', [SchoolFeeController::class, 'destroy'])->name('admin.payments.destroy');
        Route::put('/payments/{id}/add', [SchoolFeeController::class, 'addPayment'])->name('admin.payments.add');
        Route::get('/payments/{paymentName}/history', [SchoolFeeController::class, 'history'])->name('admin.payments.history');
        Route::get('/payment-requests', [SchoolFeeController::class, 'viewRequests'])->name('admin.payment.requests');
        Route::post('/payment-requests/{id}/approve', [SchoolFeeController::class, 'approveRequest'])->name('admin.payment.requests.approve');
        Route::post('/payment-requests/{id}/deny', [SchoolFeeController::class, 'denyRequest'])->name('admin.payment.requests.deny');
        Route::get('/payment-requests/check-new', [SchoolFeeController::class, 'checkNewRequests'])
            ->name('admin.payment-requests.check-new');
        Route::delete('/payments/history/{id}', [SchoolFeeController::class, 'deleteHistory'])->name('admin.payments.history.delete');
        Route::post('/payments/bulk-add-payment', [SchoolFeeController::class, 'bulkAddPayment'])->name('admin.payments.bulkAddPayment');
        Route::post('/payments/{paymentName}/add-students', [SchoolFeeController::class, 'addStudents'])->name('admin.payments.addStudents');
        Route::post('/payments/bulk-remove', [SchoolFeeController::class, 'bulkRemoveStudents'])->name('admin.payments.bulkRemoveStudents');
        Route::get('/payments/show/{paymentName}', [SchoolFeeController::class, 'show'])->name('admin.school-fees.show');
    });

// TEACHER DASHBOARD ROUTES
Route::prefix('teacher')
    ->middleware([
        'auth',
        'verified',
        \App\Http\Middleware\RoleMiddleware::class . ':teacher',
        \App\Http\Middleware\CheckUserStatus::class,
    ])
    ->group(function () {

        // Account Settings
        Route::get('/accountSettings', [TeacherController::class, 'accountSettings'])->name('teacher.account.settings');
        Route::put('/accountSettings/{id}', [TeacherController::class, 'updateTeacher'])->name('teacher.update');

        // Class
        Route::get('/myClasses', [TeacherController::class, 'myClasses'])->name('teacher.myClasses');
        Route::get('/myStudents', [TeacherController::class, 'myStudents'])->name('teacher.my.students');
        Route::get('/myClass/{grade_level}/{section}', [TeacherController::class, 'myClass'])->name('teacher.myClass');
        Route::get('/mySchedule/{grade_level}/{section}', [TeacherController::class, 'mySchedule'])->name('teacher.mySchedule');
        Route::get('/myClassMasterList/{grade_level}/{section}', [TeacherController::class, 'myClassMasterList'])->name('teacher.myClassMasterList');
        Route::post('/update-grade-permission', [TeacherController::class, 'updateGradePermission'])->name('teacher.update.grade.permission');

        // Attendance
        Route::get('/myAttendanceRecord/{grade_level}/{section}', [TeacherController::class, 'myAttendanceRecord'])->name('teacher.myAttendanceRecord');
        Route::get('/attendanceHistory/{grade_level}/{section}/{date?}/{schedule_id?}', [TeacherController::class, 'attendanceHistory'])->name('teacher.attendanceHistory');
        Route::post('/submitAttendance', [TeacherController::class, 'submitAttendance'])->name('teacher.submitAttendance');
        Route::get('/attendance/{grade}/{section}/scan/{date?}/{schedule_id?}', [TeacherController::class, 'showScanner'])->name('teacher.scanAttendance');
        Route::post('/attendance/qr-mark', [TeacherController::class, 'markAttendanceFromQR'])->name('teacher.markAttendanceFromQR');
        Route::post('/manual-attendance', [TeacherController::class, 'markManualAttendance'])->name('teacher.markManualAttendance');
        Route::post('/attendance/auto-mark-absent', [AttendanceController::class, 'autoMarkAbsent'])->name('attendance.autoMarkAbsent');

        // Subjects
        Route::get('/subjects/{grade_level}/{section}', [TeacherController::class, 'myClassSubject'])->name('teacher.myClassSubject');
        Route::post('/class/{grade_level}/{section}/subjects/create', [TeacherController::class, 'createSubject'])->name('teacher.subjects.create');
        Route::get('/class/{grade_level}/{section}/subjects/{subject_id}/view', [TeacherController::class, 'viewSubject'])->name('teacher.subjects.view');
        Route::delete('/class/{grade_level}/{section}/subjects/{subject_id}/delete', [TeacherController::class, 'deleteSubject'])->name('teacher.subjects.delete');
        Route::post('/class/{grade_level}/{section}/subjects/{subject_id}/save-grades', [TeacherController::class, 'saveGrades'])->name('teacher.subjects.saveGrades');
        Route::delete('/subjects/{grade_level}/{section}/{subject_id}/grades/{student_id}/{quarter}', [TeacherController::class, 'deleteGrade'])->name('teacher.subjects.deleteGrade');
        Route::get('/class/{grade_level}/{section}/subjects/{subject_id}/export', [TeacherController::class, 'exportQuarterlyGrades'])->name('teacher.subjects.export');

        // Student Reports
        Route::get('/student/{student_id}/report_card/export', [TeacherController::class, 'studentReportCard'])->name('teacher.student.card');
        Route::get('/student/form10/{student_id}', [TeacherController::class, 'studentForm10'])->name('teacher.student.form10');
        Route::post('/student/bulk-print-grades', [TeacherController::class, 'studentGradeSlip'])->name('teacher.print.grade.slip');
        Route::get('/studentInfo/{id}', [TeacherController::class, 'studentInfo'])->name('teacher.student.info');
        Route::get('/editStudentInfo/{id}', [TeacherController::class, 'editStudentInfo'])->name('teacher.edit.student');
        Route::post('/updateStudentInfo/{id}', [TeacherController::class, 'updateStudentInfo'])->name('teacher.update.student');

        // Payments
        Route::get('/classes/{grade_level}/{section}/payments', [SchoolFeeController::class, 'index'])->name('teacher.payments.index');
        Route::get('/classes/{grade_level}/{section}/payments/{paymentName}', [SchoolFeeController::class, 'show'])->name('teacher.payments.show');
        Route::post('/classes/{grade_level}/{section}/payments', [SchoolFeeController::class, 'create'])->name('teacher.payments.create');

        // Export SF2
        Route::get('/export-attendance', function () {
            $controller = app(AttendanceController::class);
            $data = $controller->getAttendanceExportData();
            $schoolYear = $data['selectedYear'] ?? 'UnknownYear';
            $gradeLevel = $data['class']->formatted_grade_level ?? 'UnknownGrade';
            $section = $data['class']->section ?? 'UnknownSection';
            $data['adviserName'] = Auth::user()->full_name;
            $fileName = "SBESQR_SF2_{$schoolYear}_{$gradeLevel} - {$section}.xlsx";
            return Excel::download(new SF2Export($data), $fileName);
        })->name('export.sf2');
    });

// PARENT DASHBOARD ROUTES
Route::prefix('parent')
    ->middleware([
        'auth',
        'verified',
        \App\Http\Middleware\RoleMiddleware::class . ':parent',
        \App\Http\Middleware\CheckUserStatus::class,
    ])
    ->group(function () {

        // Account Settings
        Route::get('/accountSettings', [ParentController::class, 'accountSettings'])->name('parent.account.settings');
        Route::put('/accountSettings/{id}', [ParentController::class, 'updateParent'])->name('parent.update');

        Route::get('/children', [ParentController::class, 'children'])->name('parent.children.index');
        Route::get('/children/{id}', [ParentController::class, 'showChild'])->name('parent.children.show');
        Route::get('/school-fees', [ParentController::class, 'schoolFees'])->name('parent.school-fees.index');
        Route::get('/school-fees/{paymentName}', [ParentController::class, 'showSchoolFee'])->name('parent.school-fees.show');
        Route::post('/payment/{id}/add', [ParentController::class, 'addPayment'])->name('parent.addPayment');
        Route::get('/announcements', [ParentController::class, 'announcements'])->name('parent.announcements.index');
        Route::get('/sms-logs', [ParentController::class, 'smsLogs'])->name('parent.sms-logs.index');
        Route::post('/payments/{payment}/pay', [SchoolFeeController::class, 'pay'])->name('parent.payments.pay');

        // Attendance for Parent
        Route::get('/student/{student}/attendance/{schoolYearId}/{classId}/{year}/{month}', [AttendanceController::class, 'fetchMonth'])
            ->name('attendance.fetchMonth');

        Route::get('/parent/check-attempts/{paymentId}', [ParentController::class, 'checkAttempts'])
            ->name('parent.check-attempts');
    });

// Announcement Management (on ADMIN Dashboard)
Route::middleware('auth')->prefix('announcements')->name('announcements.')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('index');
    Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
    Route::post('/', [AnnouncementController::class, 'store'])->name('store');
    Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
    Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
    Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');
    // Route::post('upload-image', [AnnouncementController::class, 'uploadImage'])->name('uploadImage');
});

// ANNOUNCEMENT & USER SEARCH ROUTES
Route::get('/announcements/{id}/show-ajax', [AnnouncementController::class, 'showAjax'])->name('announcements.showAjax');
Route::post('/announcements/upload-image', [AnnouncementController::class, 'uploadImage'])->name('announcements.uploadImage');
Route::get('/users/search', [AnnouncementController::class, 'searchUser'])->name('search.user');

// PUSH NOTIFICATIONS
Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
Route::delete('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

// GOOGLE LOGIN
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// STUDENT SEARCH ROUTES (For Payments)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/class-students/search', [StudentController::class, 'classStudentSearch'])
        ->name('class-students.search');
    Route::get('/class-students/search/exclude-payment', [StudentController::class, 'classStudentSearchExcludePayment'])
        ->name('class-students.search.exclude-payment');
});
