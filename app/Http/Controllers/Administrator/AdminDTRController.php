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
            ->whereHas('user', function($q) use($req){
                $q->where('lname', 'like',  $req->lname . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
        return $data;
    }
    public function getUserDTR($id){
        $userid = $id;

        return DailyTimeRecord::whereMonth('2')
        ->orderBy('date_record')
        ->get();

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
        $data = DailyTimeRecord::with(['user'])
            ->whereHas('user', function($q) use ($id){
                $q->where('user_id', $id);
            })
        ->get();

        return view('administrator.display-dtr')
            ->with('id', $id)
            ->with('data', $data);
    }

}
