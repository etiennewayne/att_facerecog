<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\DailyTimeRecord;
use Illuminate\Http\Request;


use App\Models\User;

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

        //return $req;
        if($req->searchdate == 'null'){
            $data = DailyTimeRecord::with(['user'])
                ->whereHas('user', function($q) use($req){
                    $q->where('lname', 'like',  $req->lname . '%');
                })
                ->orderBy($sort[0], $sort[1])
                ->paginate($req->perpage);
        }else{
            $ndate = date("Y-m-d", strtotime($req->searchdate)); //convert to date format UNIX
            //return $ndate;

            $data = DailyTimeRecord::with(['user'])
                ->whereHas('user', function($q) use($req){
                    $q->where('lname', 'like',  $req->lname . '%');
                })
                ->whereDate('dt_record', $ndate)
                ->orderBy($sort[0], $sort[1])
                ->paginate($req->perpage);
        }

        
        return $data;
    }
    public function getUserDTR(Request $req){
        //return $req;
        $half = $req->half;
        $user = $req->user;
        $month = $req->month;
        $year = $req->year;

        if($half == 'first'){
            return DailyTimeRecord::whereMonth('dt_record', $month)
                ->whereYear('dt_record', $year)
                ->whereDay('dt_record', '>=', 1)
                ->whereDay('dt_record', '<=', 15)
                ->where('user_id', $user)
                ->orderBy('dt_record', 'desc')
                ->get();
        }else{
            return DailyTimeRecord::whereMonth('dt_record', $month)
                ->whereYear('dt_record', $year)
                ->whereDay('dt_record', '>=', 16)
                ->whereDay('dt_record', '<=', 31)
                ->where('user_id', $user)
                ->orderBy('dt_record', 'desc')
                ->get();
        }

    }

    public function create(){
        return view('administrator.daily-time-record-create')
                ->with('id', 0);
    }

    public function store(Request $req){
        //return $req;


        $nTime = date("H:i", strtotime($req->nDateTime)); //convert to date format UNIX
        $ndate = date("Y-m-d", strtotime($req->nDateTime)); //convert to date format UNIX
        $nDateTime = date("Y-m-d H:i", strtotime($req->nDateTime)); //convert to date format UNIX


        DailyTimeRecord::create([
            'time_status' => $req->t_status,
            'user_id' => $req->user_id,
            'time_record' => $nTime,
            'date_record' => $ndate,
            'dt_record' => $nDateTime
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }

    public function edit($id){
        $data = DailyTimeRecord::with(['user'])->find($id);

        return view('administrator.daily-time-record-create')
            ->with('id', $id)
            ->with('data', $data);
    }

    public function update(Request $req, $id){

        $nTime = date("H:i", strtotime($req->nDateTime)); //convert to date format UNIX
        $ndate = date("Y-m-d", strtotime($req->nDateTime)); //convert to date format UNIX
        $nDateTime = date("Y-m-d H:i", strtotime($req->nDateTime)); //convert to date format UNIX

        $data = DailyTimeRecord::find($id);
        $data->time_status = $req->t_status;
        $data->time_record = $nTime;
        $data->date_record = $ndate;
        $data->dt_record = $nDateTime;

        $data->save();

        return response()->json([
            'status' => 'updated'
        ],200);
    }


    public function displayDTR($id){

        // $data = DailyTimeRecord::with(['user'])
        //     ->whereHas('user', function($q) use ($id){
        //         $q->where('user_id', $id);
        //     })
        // ->get();
        $user = User::where('user_id', $id)
            ->with(['salary_level'])
            ->first();

        return view('administrator.display-dtr')
            ->with('id', $id)
            ->with('user', $user);
    }

    public function destroy($id){
        DailyTimeRecord::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ],200);
    }

}
