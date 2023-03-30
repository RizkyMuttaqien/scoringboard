<?php

namespace App\Models;

use App\Models\Klasemen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'kota'];

    function Klasemen()
    {
        return $this->hasOne(Klasemen::class);
    }
}
