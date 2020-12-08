<?php

namespace App\Http\Controllers\API;

use App\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;

class YogaSessionController extends Controller
{
    public function index() {
        $yogaSession = Session::with(['user'])->paginate(5);

        return response()->json([
            "status" => 200,
            "message" => "Success get data session",
            "data" => $yogaSession
        ]);
    }

    public function show($id) {
        $yogaSession = Session::with(['user'])->find($id);

        return response()->json([
            "status" => 200,
            "message" => "Success get data session",
            "data" => $yogaSession
        ]);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start' => 'required|date|max:255',
            'duration' => 'required|integer|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 400,
                "message" => $validator->errors(),
                "data" => null
            ]);
        }

        $yogaSession = Session::create([
            'userID' => Auth::user()->ID,
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'duration' => $request->duration,
            'created' => Carbon::now()->toDateTimeString(),
            'updated' => Carbon::now()->toDateTimeString()
        ]);

        return response()->json([
            "status" => 200,
            "message" => "Success create data session",
            "data" => $yogaSession
        ]);
    }
}
