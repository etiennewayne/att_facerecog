<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    //

    //this controller is open..
    //can be access anywhere

    public function index(){

    }

    public function loadDescriptions(){
        //id is representing employee id from db
        return User::with(['descriptions'])
            ->where('role', 'EMPLOYEE')
            ->get();
    }


}
