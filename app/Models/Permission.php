<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['user_id', 'model', 'model_id', 'permission'];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
