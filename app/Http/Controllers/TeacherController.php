<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('teachers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getData()
    {
        $teachers = Teacher::query();
        return DataTables::of($teachers)
        ->addIndexColumn()
        ->addColumn('action', function($teachers){
            return '<button class="btn btn-sm btn-warning editBtn" data-id="'.$teachers->id.'">Edit</button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$teachers->id.'">Delete</button>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:255',
        ]);

        $teacher = Teacher::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Teacher Added Successfully',
            'data' => $teacher
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
        $teacher = Teacher::find($id);
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'phone' => 'nullable|string|max:255',
        ]);

        $teacher->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Teacher Updated Successfully',
            'data' => $teacher
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher Deleted Successfully'
        ]);
        
    }
}
