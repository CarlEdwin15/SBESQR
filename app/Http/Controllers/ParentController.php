<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Unauthorized');
        }

        return view('parent.index');
    }
}
