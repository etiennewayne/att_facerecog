<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SalaryLevel;
use App\Models\Branch;

class FaceRegisterController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $salaryLevels = SalaryLevel::orderBy('salary_level', 'asc')->get();
        $branches = Branch::all();

        return view('administrator.face-register')
            ->with('salaryLevels', $salaryLevels)
            ->with('branches', $branches);
    }



}
