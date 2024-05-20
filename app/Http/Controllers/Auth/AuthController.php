<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Lcobucci\JWT\Configuration;
use Exception;

class AuthController extends Controller
{
    //
    public function RegistrationForm(Request $request){
        return response()->json([],200);
    }


    public function Register(Request $request){

        try{
            $validatedData = $request->validate([
                'name'=>'required|string',
                'username'=>'required|unique:users|string',
                'password'=>'required|string|min:8',
                'role'=>'sometimes|in:admin,user',
                'photo'=>'sometimes|image|mimes:jpeg,png,jpg,gif|max:7000',

            ]);
        } catch (ValidationException $e){
            return response()->json(['errors' => $e->errors(),] ,210);
        }

        $photopath=null;

        if($request->hasFile('photo')){
            $photopath=time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'),$photopath);
        }
        User::create([
            'name'=>$request->input('name'),
            'username'=>$request->input('username'),
            'password'=>Hash::make($request->input('password')),
            'photo'=>$photopath,

        ]);

        $user=User::latest()->first();

        if($request->has('role')){
            $user->role= $request->input('role');
            $user->save();
        }
        //To handle creating an admin we need an if here
        if($user->role =='user'){
            $token=$this->CreateToken($user);
            //This is the user creaton return. It logs the user in with a token
            return response()->json(['token'=>$token,'photo'=>$photopath],200);
        }
        //This is the admin creation return, it doesn't log the user in.
        return response()->json(['user'=>$user->id,'photo'=>$photopath], 200);
    }




    public function LoginForm(){
        return response()->json([],200);
    }




    public function login(Request $request){
        try{
            $validatedData = $request->validate([
                'username'=>'required',
                'password'=>'required',
            ]);
        } catch (ValidationException $e){
            return response()->json(['errors' => $e->errors(),] ,210);
        }
        $credentials=$request->only('username', 'password');
        if(!Auth::attempt($credentials)){
            return response()->json([], Response::HTTP_UNAUTHORIZED);
        }
        
        $user=Auth::user(); 
        $photopath=$user->photo?$user->photo:null;
        $token=$this->CreateToken($user);
        return response()->json(['token'=>$token, 'role'=>$user->role,'photo'=>$photopath],200);
    }




    public function logout(Request $request){
        Auth::logout();
        return response()->json([],200);
    }




    public function CreateAdmin(Request $request){
        $role=['role'=>'admin'];
        $request->merge($role);
        return $this->Register($request);
    }


    

    public function CreateToken($user){
        $key= env('JWT_SECRET');
        $payload=[
             'iss'=>'http://127.0.0.1:8000/api/login', //issuer
             'aud'=>'http://127.0.0.1:8000', //audience
             'iat'=>time(), //time created
             'exp'=>time()+3600*24*30*2, //expiration
             'name'=>$user->name, 
             'username'=>$user->username,
             'password'=>$user->password,
             'role'=>$user->role,
             'id'=>$user->id,
             'photo'=>$user->photo,
        ];
 
        $token=JWT::encode($payload, $key, 'HS256');
        return $token;
    }

 
}
