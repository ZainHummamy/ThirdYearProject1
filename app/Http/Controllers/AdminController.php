<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Lcobucci\JWT\Configuration;
use App\Models\Trip;
use App\Models\Service;

class AdminController extends Controller
{
 
    public function CreateTrip(Request $request){
       
       try{ $validatedData= $request->validate([
            'image'=>'sometimes|image',
            'type'=> 'required|string',
            'Season_Type'=>'required|string',
            'Name_trip'=>'required|string',
            'Sallary'=>'required|integer',
            'Start_date'=>'date|date_format:Y-m-d|required',
            'End_date'=>'date|date_format:Y-m-d|required',
            'Day_count'=>'integer|required',
            'End_date_booking'=>'date|date_format:Y-m-d|required',
            //'Activitys'=>'required|array',
            //'Activitys.*'=>'string',
            'Services'=>'required|array',
            'Services.*'=>'integer|exists:services,id',
       ]);
       } catch(ValidationException $e){
        return response()->json(['error'=>$e->errors(),], 210);
       }
       $photopath=null;
       if($request->hasFile('image')){
       $photopath=time().'.'.$request->image->extension();
       $request->image->move(public_path('images'),$photopath);   
    }
      
       try {
         $trip= Trip::create([
        'photo'=> $photopath,
        'type'=> $request->input('type'),
        'season'=>$request->input('Season_Type'),
        'trip_name'=>$request->input('Name_trip'),
        'price_per_person'=>$request->input('Sallary'),
        'start_date'=>$request->input('Start_date'),
        'end_date'=>$request->input('End_date'),
        'days_count'=>$request->input('Day_count'),
        'booking_end_date'=>$request->input('End_date_booking'),
        //'activities'=>$request->input('Activitys'),
        //'services'=>$request->input('Services'),
       ]);

       //$services=$this->convertToIntArray($validatedData['Services']);
       $services=$validatedData['Services'];
       $trip->services()->attach($services);
       
    }catch(Exception $e){
        return response()->json(['error'=>$e->errors()],210);
    }

       return response()->json([],200);
    }

    public function convertToIntArray($data){
      $stringArray=explode(',',$data);
      return array_map('intval',$stringArray);
    }
}
