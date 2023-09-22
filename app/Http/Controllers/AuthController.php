<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tags;
use App\Models\UserSurvey;
use App\Models\User;

class AuthController extends Controller
{
    public function addTags(Request $request)
    {
        $tags = Tags::all();
        return view('add-tag', compact('tags'));
    }

    public function deleteTag(Request $request)
    {
        $tag = Tags::where('id', base64_decode($request->id))->delete();
        if ($tag) {
            session()->flash('success');
            return redirect()->back();
        }
        session()->flash('error');
        return redirect()->back();
    }

    public function showSurvey(Request $request)
    {
        $surveyUsers = UserSurvey::select('user_id')
            ->distinct()
            ->with('user')
            ->get();
        return view('show-survey', ['surveyUser' => $surveyUsers]);
    }

    public function showDetail(Request $request, $id)
    {
        $survey = UserSurvey::where('user_id', $id)->get();
        $count = 0;
        $prevEmail = null;
        return view('survey-detail', ['survey' => $survey, 'count' => $count, 'prevEmail' => $prevEmail]);
    }
}
