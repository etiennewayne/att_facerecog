<?php

namespace App\Http\Controllers;

use App\Models\DailyTimeRecord;
use Illuminate\Http\Request;

class DailyTimeRecordController extends Controller
{
    //


    public function storeDTR(Request $req){
        //return $req;

        $nTime = date("H:i", strtotime($req->t_time)); //convert to date format UNIX
        $ndate = date("Y-m-d", strtotime($req->t_time)); //convert to date format UNIX
        //return $nTime;

        DailyTimeRecord::create([
            'time_status' => $req->t_status,
            'user_id' => $req->t_user,
            'time_record' => $nTime,
            'date_record' => $ndate
        ]);

    }
}
