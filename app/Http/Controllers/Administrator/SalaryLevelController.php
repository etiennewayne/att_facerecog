<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SalaryLevel;

class SalaryLevelController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
        return view('administrator.salary-level');
    }

    public function getSalaryLevel(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = SalaryLevel::where('salary_level', 'like', $req->salarylevel . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        return SalaryLevel::find($id);
    }

    public function store(Request $req){
        $req->validate([
            'salary_level' => ['required', 'unique:salary_levels'],
            'salary' => ['required']
        ]);

        SalaryLevel::create([
            'salary_level' => strtoupper($req->salary_level),
            'salary' => $req->salary
        ]);
        return response()->json([
            'status' => 'saved'
        ],200);
    }

    public function update(Request $req, $id){
        $req->validate([
            'salary_level' => ['required', 'unique:salary_levels,salary_level,' .$id . ',salary_level_id'],
            'salary' => ['required']
        ]);

        $data = SalaryLevel::find($id);
        $data->salary_level = strtoupper($req->salary_level);
        $data->salary = $req->salary;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ],200);
    }

    public function destroy($id){

        SalaryLevel::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ],200);
    }
}
