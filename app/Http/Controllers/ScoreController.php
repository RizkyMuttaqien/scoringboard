<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Score;
use App\Models\Klasemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
    public function index()
    {
        $teams = Score::with('Team1')->with('Team2')->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Team',
            'data'    => $teams
        ], 200);
    }

    public function resetAll()
    {
        Score::truncate();
        Team::truncate();
        Klasemen::truncate();
        return response()->json([
            'success' => true,
            'message' => 'Sistem Reseted'
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data.*.team_1'   => 'required|different:team_2|array',
            'data.*.team_2' => 'required|array',
            'data.*.score_1' => 'required|array',
            'data.*.score_2' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        for ($i = 0; $i < count($request->all()); $i++) {
            $datasama = 0;
            $dataTerinput = 0;
            $teams = Score::where("team_1", $request[$i]['team_1'])->where("team_2", $request[$i]['team_2'])->first();
            if ($teams) {
                $datasama += 1;
            }
            if ($request[$i]['team_1'] != $request[$i]['team_2']) {
                $dataTerinput += 1;
                Score::create([
                    'team_1'     => $request[$i]['team_1'],
                    'team_2'   => $request[$i]['team_2'],
                    'score_1'   => $request[$i]['score_1'],
                    'score_2'   => $request[$i]['score_2'],
                ]);
                $point1 = 0;
                $point2 = 0;
                $seri = 0;
                $menang1 = 0;
                $menang2 = 0;
                $kalah1 = 0;
                $kalah2 = 0;
                if ($request[$i]['score_1'] == $request[$i]['score_2']) {
                    $point1 += 1;
                    $point2 += 1;
                    $seri += 1;
                } else if ($request[$i]['score_1'] > $request[$i]['score_2']) {
                    $point1 += 3;
                    $menang1 += 1;
                    $kalah2 += 1;
                } else if ($request[$i]['score_1'] < $request[$i]['score_2']) {
                    $point2 = 3;
                    $menang2 += 1;
                    $kalah1 += 1;
                }

                Klasemen::where('id', $request[$i]['team_1'])->update([
                    'main' => DB::raw('main + ' . 1),
                    'menang' => DB::raw('menang + ' . $menang1),
                    'seri' => DB::raw('seri + ' . $seri),
                    'kalah' => DB::raw('kalah + ' . $kalah1),
                    'goal_menang' => DB::raw('goal_menang + ' . $request[$i]['score_1']),
                    'goal_kalah' => DB::raw('goal_kalah + ' . $request[$i]['score_2']),
                    'point' => DB::raw('point + ' . $point1),
                ]);
                Klasemen::where('id', $request[$i]['team_2'])->update([
                    'main' => DB::raw('main + ' . 1),
                    'menang' => DB::raw('menang + ' . $menang2),
                    'seri' => DB::raw('seri + ' . $seri),
                    'kalah' => DB::raw('kalah + ' . $kalah2),
                    'goal_menang' => DB::raw('goal_menang + ' . $request[$i]['score_2']),
                    'goal_kalah' => DB::raw('goal_kalah + ' . $request[$i]['score_1']),
                    'point' => DB::raw('point + ' . $point2),
                ]);
            } else {
                $datasama += 1;
            }
        }
        if ($datasama > 0) {
            return response()->json([
                'success' => true,
                'message' => $dataTerinput . ' pertandingan berhasil di tambahkan ' . $datasama . ' Gagal di tambahkan karena data yang sama'
            ], 201);
        } else {
            return response()->json([
                'success' => true,
                'message' => $dataTerinput . ' pertandingan berhasil di tambahkan'
            ], 201);
        }
    }
}
