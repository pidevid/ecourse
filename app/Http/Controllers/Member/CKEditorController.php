<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|file|image|max:5120',
        ]);

        $path = $request->file('upload')->store('ckeditor', 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
