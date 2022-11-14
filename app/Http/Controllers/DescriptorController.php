<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Descriptor;



class DescriptorController extends Controller
{
    //


    public function store(Request $req){
        
        return $req;

        Descriptor::create([
            'name' => $req->name,
            'descriptor' => $req->descriptor
        ]);

    }
}
