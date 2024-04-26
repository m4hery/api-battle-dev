<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChocolatCommande extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
