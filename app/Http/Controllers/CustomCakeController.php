<?php

namespace App\Http\Controllers;

use App\Models\CustomCake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

class CustomCakeController extends Controller
{
    public function index(Request $request) {
        $data = CustomCake::orderByDesc('id')->with('user')->where('status', $request->status)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request) {
        $image = "";
        if($request->hasFile('image')) {
            $image = $request->image->store('custom-cakes');
            $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            $img->save();
        }

        $data = CustomCake::create([
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'message' => $request->message,
            'remarks' => $request->remarks,
            "image" => $image,
            "status" => $request->status,
        ]);

        return response()->json([
            'message' => "Custom cake has been added.",
            'data' => $data
        ]);
    }

    public function show($id) {
        $data = CustomCake::where('id', $id)->with('user')->first();

        return response()->json([
            'data' => $data
        ]);
    }


    public function update(Request $request, $id) {
        $data = CustomCake::where('id', $id)->first();

        $data->update($request->all());

        return response()->json([
            'message' => "Custom cake has been updated.",
            'data' => $data
        ]);
    }

    public function delete($id) {
        $data = CustomCake::where('id', $id)->first();

        $data->delete();

        return response()->json([
            'message' => "Custom cake has been deleted.",
        ]);
    }

    public function getUsersCake(Request $request) {
        $id = Auth::user()->id;
        $status = $request->status;

        $data = CustomCake::where('user_id', $id)->orderByDesc('id')->where('status', $status)->get();

        return response()->json([
            'message' => "Fetch Success",
            'data' => $data,
            'status' => $status
        ]);
    }
}
