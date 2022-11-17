<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Employee;
use App\Models\Descriptor;

class EmployeeController extends Controller
{
    //

    public function index(){

    }

    public function loadDescriptors(){
        //id is representing employee id from db

        return Employee::with(['descriptors'])->get();

        
    }
    public function store(Request $req){
        
        return $req;

        try{

        }catch(\Exception $e){
            return $e->getMessage();
        }

        DB::transaction(function () use($req, &$dataArray)  {
            //insert rating in ratings table
            $data = Employee::create([
                'lname' => $req->lname,
                'fname' => $req->fname,
                'mname' => $req->mname,
                'suffix' => $req->suffix,
                'sex' => $req->sex,
                'contact_no' => $req->contact_no,
            ]);
           //------------------------------

           //create array for ratings
           $dataArray = array();
           foreach ($req->descriptors as $descriptor){
                $temp = array([
                    'employee_id' => $data->employee_id,
                    'descriptor' => json_encode($descriptor),
               ]);
               $dataArray = array_merge($dataArray, $temp);
           }
           Descriptor::insert($dataArray);
        }); //<--close DB Transaction

        
        return response()->json(['status' => 'saved'], 200);

    }
}
