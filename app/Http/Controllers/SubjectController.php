<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('subjects.index');
    }

    public function getData()
    {
        $subjects = Subject::query();
        return DataTables::of($subjects)
        ->addIndexColumn()
        ->addColumn('action', function($subject){
            return '<button class="btn btn-sm btn-warning editBtn" data-id="'.$subject->id.'">Edit</button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$subject->id.'">Delete</button>
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
            'code' => 'nullable|string|max:255'
        ]);

        $subject = Subject::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Subject Added Successfully',
            'data' => $subject
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
        $subject = Subject::find($id);
        return response()->json($subject);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        $subject->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Subject Updated Successfully',
            'data' => $subject
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subject = Subject::find($id);
        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject Deleted Successfully'
        ]);
    }
}
