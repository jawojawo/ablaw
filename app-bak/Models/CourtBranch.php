<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourtBranch extends Model
{
    use HasFactory;
    protected $fillable = [
        'region',
        'city',
        'type',
        'branch',
        'judge',
    ];
    public function lawCase()
    {
        return $this->hasMany(LawCase::class);
    }
    public function getFormattedCourtAttribute()
    {

        return "$this->city / $this->branch";
        // return "$this->region / $this->city / $this->type / $this->branch";
    }
}
