<?php

namespace App\Http\Controllers;
use App\Models\Polygons;
use Illuminate\Http\Request;

class PolygonController extends Controller
{

    public function __construct()
    {
        $this->polygon = new Polygons();
    } //variabel global untuk memanggil modal polygon

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'geom' => 'required'
        ], [
            'name.required' => 'Name is required',
            'geom.required' => 'Location is required'
        ]);

        // Data
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom
        ];

        // Create Polygon
       if(!$this->polygon->create($data)) {
            return redirect()->back()->with('error', 'Failed to create polygon');
        }

        // Redirect To Map
        return redirect()->back()->with('success', 'Polygon created successfully');
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
