<?php

namespace App\Http\Controllers;

use App\Models\Points;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function __construct()
    {
        $this->point = new Points();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points = $this->point->points();

        foreach ($points as $p) { //looping data
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature
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
        // Validate Request
        $request->validate([
            'name' => 'required',
            'geom' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,tiff,gif|max:10000' // 10MB
        ], [
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
                $filename = time() . '_point.' . $image->getClientOriginalExtension();
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

        // Create Point
       if(!$this->point->create($data)) {
            return redirect()->back()->with('error', 'Failed to create point');
        }

        // Redirect To Map
        return redirect()->back()->with('success', 'Point created successfully');
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
