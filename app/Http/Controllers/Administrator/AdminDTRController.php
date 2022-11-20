<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\DailyTimeRecord;
use Illuminate\Http\Request;

class AdminDTRController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('administrator.daily-time-records');
    }

    public function getDTRs(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = DailyTimeRecord::with(['user'])
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function create(){
        return view('administrator.daily-time-record-create');
    }

    public function store(Request $req){

        //return $req;

        $nTime = date("H:i", strtotime($req->t_time)); //convert to date format UNIX
        $ndate = date("Y-m-d", strtotime($req->t_time)); //convert to date format UNIX
        //return $nTime;

        DailyTimeRecord::create([
            'time_status' => $req->t_status,
            'user_id' => $req->user_id,
            'time_record' => $nTime,
            'date_record' => $ndate
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }

}
