<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('classes.index');
    }

    public function getData()
    {
        $classes = SchoolClass::query();
        return DataTables::of($classes)->addIndexColumn()->addColumn('action', function($class){
            return '
                <button class="btn btn-sm btn-warning editBtn" data-id="'.$class->id.'">Edit</button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$class->id.'">Delete</button>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);
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
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
        ]);

        $class = SchoolClass::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Class Added Successfully',
            'data' => $class
        ]);

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
    public function edit($id)
    {
        $schoolClass = SchoolClass::find($id);
        return response()->json($schoolClass);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    { 
        $schoolClass = SchoolClass::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
        ]);

        $schoolClass->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Class Updated Successfully',
            'data' => '$schoolClass'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schoolClass = SchoolClass::find($id);
        $schoolClass->delete();

        return response()->json([
            'success' => true,
            'message' => 'Class Deleted Successfully'
        ]);
    }
}
