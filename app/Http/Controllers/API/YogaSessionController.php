<?php

namespace App\Http\Controllers\API;

use App\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;

class YogaSessionController extends Controller
{
    public function index() {
        $yogaSession = Session::with(['user'])->paginate(5);

        return response()->json([
            "message" => "Success get data session",
            "data" => $yogaSession
        ], 200);
    }

    public function show($id) {
        $yogaSession = Session::with(['user'])->find($id);

        return response()->json([
            "message" => "Success get data session",
            "data" => $yogaSession
        ], 200);
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
                "message" => $validator->errors(),
                "data" => null
            ], 400);
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
            "message" => "Success create data session",
            "data" => $yogaSession
        ], 200);
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start' => 'required|date|max:255',
            'duration' => 'required|integer|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
                "data" => null
            ], 400);
        }

        $yogaSession = Session::find($id);

        if ($yogaSession->userID != Auth::user()->ID) {
            return response()->json([
                "message" => "can't update other data session",
                "data" => null
            ], 400);
        }

        $yogaSession->ID = $yogaSession->ID;
        $yogaSession->name = $request->name;
        $yogaSession->description = $request->description;
        $yogaSession->start = $request->start;
        $yogaSession->duration = $request->duration;
        $yogaSession->updated = Carbon::now()->toDateTimeString();
        $yogaSession->save();

        return response()->json([
            "message" => "Success update data session",
            "data" => $yogaSession
        ], 200);
    }

    public function delete($id) {
        $yogaSession = Session::find($id);

        if ($yogaSession->userID != Auth::user()->ID) {
            return response()->json([
                "message" => "can't delete other data session",
                "data" => null
            ], 400);
        }

        $yogaSession->delete();

        return response()->json([
            "message" => "Success delete data session",
            "data" => null
        ], 200);
    }
}
