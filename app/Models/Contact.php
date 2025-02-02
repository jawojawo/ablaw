<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = ['contact_type', 'contact_value', 'contact_label'];

    public function contactable()
    {
        return $this->morphTo();
    }
}
