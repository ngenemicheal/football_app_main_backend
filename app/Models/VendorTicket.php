<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTicket extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_code', 'vendor_id', 'matchday_id', 'status'];

    public function matchday()
    {
        return $this->belongsTo(Matchday::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class);
    }
}
