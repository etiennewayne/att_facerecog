<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
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

    //for browse employee modal
    //for employee list only
    public function getBrowseEmployees(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = User::where('lname', 'like', $req->lname . '%')
            ->where('fname', 'like', $req->fname . '%')
            ->where('role', 'EMPLOYEE')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


}
