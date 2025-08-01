<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IdController;
use App\Http\Controllers\HomeController;
use App\Exports\TeachersExport;
use App\Exports\SF2Export;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// ADMIN DASHBOARD ROUTES

// Teachers management (on ADMIN dashboard)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Google login
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Facebook login
Route::get('/auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

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

Route::get('/export-teachers', function () {
    return Excel::download(new TeachersExport, 'List of teachers.xlsx');
})->name('export.teachers');


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
Route::get('/classes/{grade_level}/{section}/attendance', [AttendanceController::class, 'attendance'])->name('classes.attendance');







// TEACHER DASHBOARD ROUTES

//List of Teacher's Students (on teacher Dashboard)
Route::get('/myStudents/{grade_level}/{section}', [TeacherController::class, 'myStudents'])->name('teacher.myStudents');

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

Route::get('/teacher-export-attendance', function () {
    $controller = app(AttendanceController::class);
    $data = $controller->getAttendanceExportData(); // Custom method to return needed data

    return Excel::download(new SF2Export($data), 'SF2.xlsx');
})->name('export.sf2');

//List of Student's Info (on teacher Dashboard)
Route::get('/studentInfo/{id}', [TeacherController::class, 'studentInfo'])->name('teacher.student.info');
Route::get('/editStudentInfo/{id}', [TeacherController::class, 'editStudentInfo'])->name('teacher.edit.student');
Route::post('/updateStudentInfo/{id}', [TeacherController::class, 'updateStudentInfo'])->name('teacher.update.student');







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
