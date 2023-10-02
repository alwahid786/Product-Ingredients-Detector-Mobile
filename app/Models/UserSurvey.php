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
        // $results = DB::table('user_surveys')->select('user_id','question','answer')->get()->toArray();

        // $distinctUserIds = [];

        // foreach ($results as $result) {
        //     if (!in_array($result->user_id, $distinctUserIds)) {
        //         $distinctUserIds[] = $result->user_id;
        //     } else {
        //         $result->user_id = null;
        //     }
        // }
        // return $result;
        $results = DB::table('user_surveys')->select('user_id', 'question', 'answer')->get()->toArray();

        $distinctUserIds = [];
        $modifiedResults = [];

        foreach ($results as $result) {
            if (!in_array($result->user_id, $distinctUserIds)) {
                $distinctUserIds[] = $result->user_id;
                $modifiedResults[] = $result;
            } else {
                $result->user_id = null;
                $modifiedResults[] = $result;
            }
        }

        return $modifiedResults;
    }
}
