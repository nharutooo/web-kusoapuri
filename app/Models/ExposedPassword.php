<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExposedPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'password',
        'source',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}