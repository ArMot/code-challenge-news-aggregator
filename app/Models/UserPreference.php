<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    /** @use HasFactory<\Database\Factories\UserPreferenceFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'categories', 'sources', 'authors'];

    protected $casts = [
        'categories' => 'array',
        'sources' => 'array',
        'authors' => 'array',
    ];
}
