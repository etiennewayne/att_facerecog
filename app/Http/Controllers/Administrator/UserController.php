<?php

namespace App\Http\Controllers\Administrator;


use App\Models\Descriptor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\SalaryLevel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){
        $salaryLevels = SalaryLevel::orderBy('salary_level', 'asc')->get();

        return view('administrator.user')
            ->with('salaryLevels', $salaryLevels); //blade.php
    }

    public function getUsers(Request $req){
        $sort = explode('.', $req->sort_by);

        $users = User::where('lname', 'like', $req->lname . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $users;
    }

    public function show($id){
        return User::find($id);
    }

    public function store(Request $req){
        //this will create random unique QR code
        //$qr_code = substr(md5(time() . $req->lname . $req->fname), -8);

        $validate = $req->validate([
            'username' => ['required', 'max:50', 'unique:users'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'sex' => ['required', 'string', 'max:20'],
            // 'email' => ['required', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'role' => ['required', 'string'],
        ]);

        DB::transaction(function () use($req, &$dataArray)  {
            //insert rating in ratings table
            $data = User::create([
                'username' => $req->username,
                'password' => Hash::make($req->password),
                'lname' => strtoupper($req->lname),
                'fname' => strtoupper($req->fname),
                'mname' => strtoupper($req->mname),
                'sex' => $req->sex,
                'contact_no' => $req->contact_no,
                'role' => $req->role,
                'salary_level_id' => $req->salary_level_id,
                'province' => $req->province,
                'city' => $req->city,
                'barangay' => $req->barangay,
                'street' => strtoupper($req->street)
            ]);
           //------------------------------

            if($req->role != 'ADMINISTRATOR'){
                //create array for ratings
                $dataArray = array();
                foreach ($req->descriptions as $description){
                    $temp = array([
                        'user_id' => $data->user_id,
                        'descriptions' => json_encode($description),
                    ]);
                    $dataArray = array_merge($dataArray, $temp);
                }
                Descriptor::insert($dataArray);
            }

        }); //<--close DB Transaction


        return response()->json(['status' => 'saved'], 200);

    }

    public function update(Request $req, $id){
        $validate = $req->validate([
            'username' => ['required', 'max:50', 'unique:users,username,'.$id.',user_id'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'sex' => ['required', 'string', 'max:20'],
            // 'email' => ['required', 'unique:users,email,'.$id.',user_id'],
            'role' => ['required', 'string'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'barangay' => ['required', 'string'],
        ]);

        $data = User::find($id);
        $data->username = $req->username;
        $data->lname = strtoupper($req->lname);
        $data->fname = strtoupper($req->fname);
        $data->mname = strtoupper($req->mname);
        $data->sex = $req->sex;
       // $data->email = $req->email;
        $data->role = $req->role;
        $data->salary_level_id = $req->salary_level_id;
        $data->province = $req->province;
        $data->city = $req->city;
        $data->barangay = $req->barangay;
        $data->street = strtoupper($req->street);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ]);
    }


    public function resetPassword(Request $req, $id){

        $req->validate([
            'password' => ['required', 'confirmed']
        ]);

//        if($req->password != $req->password_confirmation){
//            return response()->json([
//                'status' => 'not_matched'
//            ], 422);
//        }

        $user = User::find($id);
        $user->password = Hash::make($req->password);
        $user->save();

        return response()->json([
            'status' => 'changed'
        ],200);


    }

    public function destroy($id){
        User::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ],200);
    }
}
