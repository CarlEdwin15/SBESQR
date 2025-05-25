<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeAndSectionController extends Controller
{
    public function allGradeLevels()
    {
        return view('admin.grade_levels.allGradeLevels');
    }

    public function kindergarten()
    {
        $teachers = User::where('grade_level_assigned', 'kindergarten')->get();

        $students = Classes::where('student_grade_level', 'kindergarten')->get();

        return view('admin.grade_levels.kindergarten', compact('teachers', 'students'));
    }

    public function grade1()
    {
        $teachers = User::where('grade_level_assigned', 'grade1')->get();

        $students = Student::where('student_grade_level', 'grade1')->get();

        return view('admin.grade_levels.grade1', compact('teachers', 'students'));
    }

    public function grade2()
    {
        $teachers = User::where('grade_level_assigned', 'grade2')->get();

        $students = Student::where('student_grade_level', 'grade2')->get();

        return view('admin.grade_levels.grade2', compact('teachers', 'students'));
    }

    public function grade3()
    {
        $teachers = User::where('grade_level_assigned', 'grade3')->get();

        $students = Student::where('student_grade_level', 'grade3')->get();

        return view('admin.grade_levels.grade3', compact('teachers', 'students'));
    }

    public function grade4()
    {
        $teachers = User::where('grade_level_assigned', 'grade4')->get();

        $students = Student::where('student_grade_level', 'grade4')->get();

        return view('admin.grade_levels.grade4', compact('teachers', 'students'));
    }

    public function grade5()
    {
        $teachers = User::where('grade_level_assigned', 'grade5')->get();

        $students = Student::where('student_grade_level', 'grade5')->get();

        return view('admin.grade_levels.grade5', compact('teachers', 'students'));
    }

    public function grade6()
    {
        $teachers = User::where('grade_level_assigned', 'grade6')->get();

        $students = Student::where('student_grade_level', 'grade6')->get();

        return view('admin.grade_levels.grade6', compact('teachers', 'students'));
    }
}
