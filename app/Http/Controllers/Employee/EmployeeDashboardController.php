<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        //$this->middleware('user');
    }

    public function index(){
        $user = Auth::user();

        return view('employee.employee-dashboard')
            ->with('user', $user);
    }

}
