<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question',
        'answer',
        'email'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'device_id', 'user_id');
    }

}
