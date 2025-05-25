<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Exports\TeachersExport;
use App\Http\Controllers\GradeAndSectionController;
use App\Http\Controllers\TeacherController;
use App\Models\GradeAndSection;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});

        // Teachers management (on admin dashboard)
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


        //Students management (on admin dashboard)
Route::get('/addStudent', [StudentController::class, 'create'])->name('add.student');

Route::post('/addStudent', [StudentController::class, 'store'])->name('store.student');

Route::get('/showAllStudents', [StudentController::class, 'show'])->name('show.students');

Route::get('/editStudent/{id}', [StudentController::class, 'edit'])->name('edit.student');

Route::post('/updateStudent/{id}', [StudentController::class, 'update'])->name('update.student');

Route::delete('/deleteStudent/{id}', [StudentController::class, 'destroy'])->name('delete.student');

Route::get('/students/{id}', [StudentController::class, 'showStudentInfo'])->name('student.info');


        //Grade and Section view (on admin dashboard)
Route::get('/allGradeLevels', [GradeAndSectionController::class, 'allGradeLevels'])->name('all.grade.levels');
Route::get('/kindergarten', [GradeAndSectionController::class, 'kindergarten'])->name('kindergarten');
Route::get('/grade1', [GradeAndSectionController::class, 'grade1'])->name('grade1');
Route::get('/grade2', [GradeAndSectionController::class, 'grade2'])->name('grade2');
Route::get('/grade3', [GradeAndSectionController::class, 'grade3'])->name('grade3');
Route::get('/grade4', [GradeAndSectionController::class, 'grade4'])->name('grade4');
Route::get('/grade5', [GradeAndSectionController::class, 'grade5'])->name('grade5');
Route::get('/grade6', [GradeAndSectionController::class, 'grade6'])->name('grade6');




        //List of Teacher's Students (on teacher Dashboard)
Route::get('/myStudents', [TeacherController::class, 'myStudents'])->name('teacher.my.students');

        //List of Student's Info (on teacher Dashboard)
Route::get('/studentInfo/{student_id}', [TeacherController::class, 'studentInfo'])->name('teacher.student.info');
Route::get('/editStudentInfo/{student_id}', [TeacherController::class, 'editStudentInfo'])->name('teacher.edit.student');
Route::post('/updateStudentInfo/{student_id}', [TeacherController::class, 'updateStudentInfo'])->name('teacher.update.student');


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
