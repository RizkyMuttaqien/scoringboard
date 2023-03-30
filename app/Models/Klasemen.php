<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasemen extends Model
{
    use HasFactory;
    protected $fillable = ['team_id', 'main', 'menang', 'seri', 'kalah', 'goal_menang', 'goal_kalah'];

    function Team()
    {
        return $this->belongsTo(Team::class, "team_id", "id");
    }
}
