<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tags;

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
}
