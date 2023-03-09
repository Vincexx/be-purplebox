<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   
    public function index()
    {
        $users = User::orderBy('id','desc')->get();

        return response()->json([
            "data" => $users
        ]);
    }

    public function store(UserRequest $request)
    {
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

        return response()->json([
            "message" => "User has been added.",
            "data" => $user
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            "data" => $user
        ], 200);
    }


    
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return response()->json([
            "message" => "User has been updated.",
            "data" => $user
        ], 200);
    }

   
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
                "message" => "User has been deleted.",
        ], 200);
    }
}
