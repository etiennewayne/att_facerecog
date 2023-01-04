<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class FaceRecogDashboardController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $id = Auth::user()->user_id;

        $user = User::where('user_id', $id)
            ->with(['branch'])
            //->select('lname', 'fname', 'mname', 'sex')
            ->first();

        return view('face-recog')
            ->with('user', $user);
    }


    public function index_face_dashboard(){
        return view('face-recog-dashboard');
    }

    public function images($folder, $filename){
        return response()->file('/labeled_images//' . $folder . '/' . $filename . '.jpg');
    }
}
