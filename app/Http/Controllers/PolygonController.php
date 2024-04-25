<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Polygons;
use App\Http\Controllers\Controller;

class PolygonController extends Controller
{
    public function __construct()
    {
        $this->polygon = new Polygons();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polygons = $this->polygon->polygons();

        foreach ($polygons as $polygon){
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geom),
                'properties' => [
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at
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
            'geom' => 'required'
        ],
        [
            'name.required' => 'Name is required',
            'geom.required' => 'Location is required'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom
        ];


        // Create Point
        if(!$this->polygon->create($data)){
            return redirect()->back()->with('error', 'Failed to create polygon');
        }

        // Redirect To Map
        return redirect()->back()->with('success', 'Polygon create Successfully');
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
