<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Associate extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'suffix',
        'info',
    ];
    public function lawCase()
    {
        return $this->hasMany(LawCase::class);
    }
}
