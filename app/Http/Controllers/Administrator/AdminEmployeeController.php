<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Employee;

class AdminEmployeeController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }



    public function index(){
        return view('administrator.admin-employee');
    }


    public function getEmployees(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Employee::where('lname', 'like', $req->lname . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        return Employee::find($id);
    }

    public function update(Request $req, $id){

        $req->validate([
            'lname' => ['required'],
            'fname' => ['required']
        ]);

        $data = Employee::find($id);

        $data->lname = strtoupper($req->lname);
        $data->fname = strtoupper($req->fname);
        $data->mname = strtoupper($req->mname);
        $data->suffix = strtoupper($req->suffix);
        $data->sex = strtoupper($req->sex);
        $data->contact_no = $req->contact_no;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ]);


    }

}
