<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    protected $fillable = ['team_1', 'team_2', 'score_1', 'score_2'];

    function Team1()
    {
        return $this->belongsTo(Team::class, "team_1", "id");
    }
    function Team2()
    {
        return $this->belongsTo(Team::class, "team_2", "id");
    }
}
