<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|max:255',
            'middle_name' => '',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'role' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);       
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'role' => $request->role,
            'contact_num' => $request->contact_num,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => "Register Successful.",
            'data' => $user,
            'access_token' => $token, 
            'token_type' => 'Bearer', 
        ], 201);
    }

    public function login(Request $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $token =  $user->createToken('token')->plainTextToken; 
            return response()->json([
                'message' => "Login Successfull",
                'data' => $user,
                'access_token' => $token, 
                'token_type' => 'Bearer', 
            ], 200); 
        } 
        else{ 
            return response()->json(['message'=>'Invalid Credentials'], 401); 
        } 
    }

    public function logout(Request $request) {
        Auth::user()->tokens()->delete();

        return response()->json([
            "message" => "Logged Out"
        ]);
    }
}
