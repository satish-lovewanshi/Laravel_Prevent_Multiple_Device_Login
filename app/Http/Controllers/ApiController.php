<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\login;
use App\Models\user;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $req){
        $new=new user;             
        $new->name=$req->name;     
        $new->email=$req->email;   
        $new->password=hash::make($req->password);
        $new->mobile=$req->mobile;
        $new->save();
        return response()->json($new);
    }
}
