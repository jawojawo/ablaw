<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativeFeeCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'amount',
    ];
    public function administrativeFees()
    {
        return $this->hasMany(AdministrativeFee::class);
    }
}
