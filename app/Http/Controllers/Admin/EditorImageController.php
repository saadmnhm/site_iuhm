<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorImageController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|file|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $file     = $request->file('image');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('editor', $filename, 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
