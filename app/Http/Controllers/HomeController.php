<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    //

    public function home(){
        return response()->json([],200);
    }

    public function AdminFunction(){
        return response()->json(["This is an admin"]);
    }
}
