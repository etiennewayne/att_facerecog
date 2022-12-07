<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\User;


class EmployeeDTRConttroller extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
        //$this->middleware('user');
    }

    public function index(){
        $id = Auth::user()->user_id;

        $user = User::where('user_id', $id)
            ->with(['salary_level'])
            ->first();

        return view('employee.employee-dtr')
            ->with('user', $user);
    }
}
