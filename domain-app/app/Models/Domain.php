<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'is_registered','expires_at'];

    protected $casts = [
        'is_registered' => 'boolean',
        'expires_at' => 'date'
    ];
}
