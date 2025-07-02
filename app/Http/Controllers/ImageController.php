<?php

namespace App\Http\Controllers;

use App\Models\Design;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'data' =>  $images
        ], 200);
    }

    public function show(Image $image)
    {

        if (!$image) {
            return response()->json(['message' => 'Image not found.'], 404);
        }
        return response()->json([
            "message" => 'success',
            "data" => $image
        ], 200);
    }


    public function update(Request $request, Image $image)
    {
        $newImage = $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $designName = $image->design->name_en;

        $imageName = $designName . '.' . $request->file('image')->getClientOriginalExtension();


        $path = $request->file('image')->storeAs('image', $imageName, 'public');


        return response()->json([
            'message' => 'Image updated successfully',
            'data' => $image,
        ], 200);
    }

}
