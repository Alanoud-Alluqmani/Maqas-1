<?php

namespace App\Http\Controllers;

use App\Models\Design;
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

    public function update(Request $request,Image $image)
    {
        $newImage=$request->validate([
            'image' => 'required|image|max:2048',
        ]);
        
        if (Storage::disk('public')->exists($image->image)) {
           Storage::disk('public')->delete($image->image);
        }

        $designName = $image->design->name_en;
        // echo $designName;
        // $newImage = $request->file('image');
        $imageName = $designName . '.' . $request->file('image')->getClientOriginalExtension();
         echo $imageName;
       // $imagePath = $image->store('image', $imageName, 'public');  

         $path = $request->file('image')->storeAs('image', $imageName, 'public');
         
        // $image->update(['image'=>$path]);
        // $image->save();

        return response()->json([
            'message' => 'Image updated successfully',
            'data' => $image,
        ],200);
    }

//   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}

