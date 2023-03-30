<?php

namespace App\Http\Controllers;

use App\Models\Klasemen;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Klasemen::with("Team")->orderBy('point', 'DESC')->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Team',
            'data'    => $teams
        ], 200);
    }

    public function show($id)
    {
        $team = Team::with("Klasemen")->findOrfail($id);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Team',
            'data'    => $team
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            'kota' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $teams = Team::where("nama", $request->nama)->first();
        if ($teams) {
            return response()->json([
                'success' => false,
                'message' => 'Team Already Exist'
            ], 403);
        }

        $team = Team::create([
            'nama'     => $request->nama,
            'kota'   => $request->kota
        ]);

        $klasemen = Klasemen::create([
            'team_id'     => $team->id
        ]);

        if ($team && $klasemen) {
            return response()->json([
                'success' => true,
                'message' => 'Team Created',
                'data'    => $team
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Team Failed to Save',
        ], 409);
    }
    public function update(Request $request, Team $team)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
            'kota' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $team = Team::findOrFail($team->id);

        if ($team) {
            $team->update([
                'nama'     => $request->nama,
                'kota'   => $request->kota
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Team Updated',
                'data'    => $team
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Team Not Found',
        ], 404);
    }
    public function destroy($id)
    {
        $team = Team::findOrfail($id);

        if ($team) {
            $team->delete();
            return response()->json([
                'success' => true,
                'message' => 'Team Deleted',
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Team Not Found',
        ], 404);
    }
}
