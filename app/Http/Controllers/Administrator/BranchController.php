<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Branch;

class BranchController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }
    
    
    public function index(){
        return view('administrator.branch');
    }

    public function getBranches(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Branch::where('branch_name', 'like', $req->branch_name . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }



    public function show($id){
        return Branch::find($id);
    }

    public function store(Request $req){
        $req->validate([
            'branch_name' => ['required', 'unique:branches']
        ]);

        Branch::create([
            'branch_name' => strtoupper($req->branch_name)
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){
        

        $req->validate([
            'branch_name' => ['required', 'unique:branches,branch_name,'. $id . ',branch_id']
        ]);

        $data = Branch::find($id);
        $data->branch_name =strtoupper($req->branch_name);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        
        Branch::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }


    
}
