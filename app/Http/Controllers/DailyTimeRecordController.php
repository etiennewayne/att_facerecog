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
        $nDateTime = date("Y-m-d H:i", strtotime($req->t_time)); //convert to date format UNIX

        $exist = DailyTimeRecord::whereDate('dt_record', $ndate)
            ->where('time_status', $req->t_status)
            ->where('user_id',  $req->t_user)
            ->exists();

        if($exist){
            return response()->json([
                'status' => 'exist'
            ], 422);
        }else{
            DailyTimeRecord::create([
                'time_status' => $req->t_status,
                'user_id' => $req->t_user,
                'dt_record' => $nDateTime
            ]);

            return response()->json([
                'status' => 'saved'
            ], 200);
        }

    }
}
