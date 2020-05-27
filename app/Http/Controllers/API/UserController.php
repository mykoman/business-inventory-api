<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\User;

class UserController extends Controller
{
    //
    public $successStatus = 200;
    /**
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){ 
        //dd('hello');
        //return response()->json(['error'=> 'Mike'], 500);
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            //$email = request
            $data['token'] =  $user->createToken('MyApp')-> accessToken;
            $data['email'] = $user->email;
            $data['name'] = $user->name;
            $data['userId']= $user->id;

             

            return response()->json(['data' => $data, 'status'=>200], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'email and password do not match!', 'status'=> 401], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            //'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], 401);            
                }
        $input = $request->all(); 
                $input['password'] = bcrypt($input['password']); 
                $user = User::create($input); 
                $data['token'] =  $user->createToken('MyApp')-> accessToken; 
                $data['email'] =  $user->email;
                $data['userId']= $user->id;

        return response()->json(['data'=>$data, 'status'=>200], $this-> successStatus); 
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user , 'status'=>200], $this-> successStatus); 
    } 

    public function updateDetails(Request $request)
    {
        $userId= $request->input('userId');
        $firstname= $request->input('firstname');
        $lastname= $request->input('lastname');
        $email= $request->input('email');
        $phone= $request->input('phone');
        $user = User::find($userId);
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->phone = $phone;
        $user->email = $email;
        $user->save();
        //let's return the updated details
        $updatedUser = User::find($userId);
        
        return response()->json(['data'=>$updatedUser, 'status'=>200], $this-> successStatus); 
        
    }
}
