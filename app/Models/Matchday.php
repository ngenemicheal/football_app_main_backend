<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matchday extends Model
{
    use HasFactory;

    protected $fillable = ['home_team', 'away_team', 'match_date', 'price'];

    public function tickets()
    {
        return $this->hasMany(VendorTicket::class);
    }
}
