<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

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
    public static function getsurveyresult(){
        $result = DB::table('user_surveys')->select('user_id','question','answer')->get()->toArray();
        return  $result;
    }
}
