<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaceRecogDashboardController extends Controller
{
    //


    public function index(){
        return view('face-recog');
    }


    public function index_face_dashboard(){
        return view('face-recog-dashboard');
    }

    public function images($folder, $filename){

        return response()->file('/labeled_images//' . $folder . '/' . $filename . '.jpg');
    }
}
