<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) { // Ensure user is authenticated
            $role = Auth::user()->role; // Fetch user role

            if ($role == 'teacher') {

                return view('teacher.index');

            } else {

                return view('admin.index');
            }
        }

        // return redirect()->route('login'); // Redirect if not authenticated
    }
}
