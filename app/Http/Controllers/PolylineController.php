<?php

namespace App\Http\Controllers;
use App\Models\Polylines;

use Illuminate\Http\Request;

class PolylineController extends Controller
{

    public function __construct()
    {
        $this->polyline = new Polylines();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polylines = $this->polyline->polylines();

        foreach ($polylines as $polyline){
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($polyline->geom),
                'properties' => [
                    'name' => $polyline->name,
                    'description' => $polyline->description,
                    'image' => $polyline->image,
                    'created_at' => $polyline->created_at,
                    'updated_at' => $polyline->updated_at
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureColllection',
            'features' => $feature,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'name' => 'required',
            'geom' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,tiff,gif|max:10000' // 10MB
        ],
        [
            'name.required' => 'Name is required',
            'geom.required' => 'Location is required',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, tiff, gif',
            'image.max' => 'Image must not exceed max 10MB'
        ]);

        // create folder images
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
         }


       // upload image
           if ($request->hasFile('image')) {
               $image = $request->file('image');
               $filename = time() . '_polyline.' . $image->getClientOriginalExtension();
               $image->move('storage/images', $filename);
           } else {
               $filename = null;
           }

           $data = [
               'name' => $request->name,
               'description' => $request->description,
               'geom' => $request->geom,
               'image' => $filename
           ];




        // Create Polyline
        if(!$this->polyline->create($data)){
            return redirect()->back()->with('error', 'Failed to create polyline');
        }

        // Redirect To Map
        return redirect()->back()->with('success', 'Polyline create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
