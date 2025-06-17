<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $images = Image::all(); 

        return response()->json([
            'message'=> 'success',
            'data'=>  $images 
        ],200); 
    }



    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        
         if (!$image) {
            return response()->json(['message' => 'Image not found.'], 404);
        }
       return response()->json([
            "message" => 'success', // Return success message in JSON format
            "data" => $image
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
{
    if ($request->hasFile('image')) {
        // Get the new image
        //$newImage = $request->file('image');

        // Delete old image if it exists
       // Storage::disk('public')->delete($image->image);

        // Store the new image
        //$imagename = time() . '.' . $newImage->getClientOriginalExtension(); // Using a unique name
       // $imagePath = $newImage->storeAs('image', 'public');
        $imagePath=$request->file('image')->store('image', 'public');

        // // Update the image record in the database
        $image->update([
            'image' => $imagePath
        ]);
    }


// public function update(Request $request, Image $image)
//     {

//         $request->validate([
//             'image' => 'required|image|max:2048'
//         ]);

//         // Delete old image
//         Storage::disk('public')->delete($image->image);

//         // Store new image
//         $path = $request->file('image')->store('image', 'public');
//         $image->update(['image' => $path]);

//         return response()->json(['message' => 'Image updated', 'data' => $image]);
//     }



    return response()->json([
        "message" => "Image updated successfully.",
        "data" => $image
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
