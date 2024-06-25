<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt' => 'nullable|string|max:255',
            'image_type' => 'required|string|in:project,task',
        ]);

        // Store the image file
        $path = $request->file('file')->store('public/' . $request->input('image_type') . '-images');

        // Create a new Image entry in the database
        $image = Image::create([
            'path' => Storage::url($path), // Store the URL path to access the image
            'alt' => $request->input('alt'),
            'image_type' => $request->input('image_type'),
        ]);

        return response()->json(['message' => 'Image uploaded successfully', 'image' => $image], 200);
    }
}
