<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Design;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Store Owner,Store Employee')->only('update');
    }

    public function index()
    {
        $images = Image::all();

        return response()->json([
            'message' => 'success',
            'data' =>  ImageResource::Collection($images),
        ], 200);
    }

    public function show(Image $image)
    {

        if (!$image) {
            return response()->json(['message' => 'Image not found.'], 404);
        }
        return response()->json([
            "message" => 'success',
            "data" => new ImageResource($image)
        ], 200);
    }

    public function update(Request $request, Image $image)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }


        $designName = $image->design->name_en;
        $extension = $request->file('image')->getClientOriginalExtension();
        $sluggedName = Str::slug($designName); // Removes spaces
        $imageName = $sluggedName . '.' . $extension;

        $path = $request->file('image')->storeAs('image', $imageName, 'public');

        $image->update([
            'image' => $path,
        ]);

        $image->refresh();

        return response()->json([
            'message' => 'Image updated successfully',
            'data' => new ImageResource($image),
        ], 200);
    }
}
