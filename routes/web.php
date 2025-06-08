<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IdController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Exports\TeachersExport;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AttendanceController;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});


// ADMIN DASHBOARD ROUTES

// Teachers management (on ADMIN dashboard)
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/showAllTeachers', [AdminController::class, 'showAllTeachers'])->name('show.teachers');

Route::post('/registerTeacher', [AdminController::class, 'registerTeacher'])->name('register.teacher');

Route::get('/editTeacher/{id}', [AdminController::class, 'editTeacher'])->name('edit.teacher');

Route::post('/updateTeacher/{id}', [AdminController::class, 'updateTeacher'])->name('update.teacher');

Route::delete('/deleteTeacher/{id}', [AdminController::class, 'deleteTeacher'])->name('delete.teacher');

Route::get('/teacherInfo/{id}', [AdminController::class, 'teacherInfo'])->name('teacher.info');

Route::get('/export-teachers', function () {
    return Excel::download(new TeachersExport, 'List of teachers.xlsx');
})->name('export.teachers');


// Students Management (on ADMIN dashboard)
Route::get('/addStudent', [StudentController::class, 'create'])->name('add.student');

Route::post('/addStudent', [StudentController::class, 'store'])->name('store.student');

Route::get('/showAllStudents', [StudentController::class, 'show'])->name('show.students');

Route::get('/editStudent/{id}', [StudentController::class, 'edit'])->name('edit.student');

Route::post('/updateStudent/{id}', [StudentController::class, 'update'])->name('update.student');

Route::delete('/deleteStudent/{id}', [StudentController::class, 'destroy'])->name('delete.student');

Route::get('/students/{id}', [StudentController::class, 'showStudentInfo'])->name('student.info');


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

Route::post('/classes/{grade_level}/{section}/addSchedule', [ScheduleController::class, 'addSchedule'])->name('classes.addSchedule');


// Attendance Management (on ADMIN dashboard)
Route::get('/classes/{grade_level}/{section}/attendance', [AttendanceController::class, 'attendance'])->name('classes.attendance');








// TEACHER DASHBOARD ROUTES

//List of Teacher's Students (on teacher Dashboard)
Route::get('/myStudents/{grade_level}/{section}', [TeacherController::class, 'myStudents'])->name('teacher.my.students');

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
