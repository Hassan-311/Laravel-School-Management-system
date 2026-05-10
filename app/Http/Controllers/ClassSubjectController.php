<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::all();
        $subjects = subject::all();
        return view('class_subject.index', compact('classes', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getData()
    {
        $classes = SchoolClass::with('subjects')->select('classes.*');
        return DataTables::of($classes)
        ->addIndexColumn()
        ->addColumn('subjects', function($class){
            if($class->subjects->isEmpty()) return '-';
            return $class->subjects->map(function($s){
                return '<span class="badge bg-success me-1">'.$s->name.'</span>
                        <button class="btn btn-sm btn-danger removeBtn"
                            data-class="'.$s->pivot->class_id.'"
                            data-subject="'.$s->pivot->subject_id.'">x</button>';
            })->implode(' ');
        })
        ->rawColumns(['subjects'])
        ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $class = SchoolClass::find($request->class_id);

        if($class->subjects->contains($request->subject_id)){
            return response()->json([
                'success' => false,
                'message' => 'This subject has already been assigned',
            ]);
        }
        $class->subjects()->attach($request->subject_id);

        return response()->json([
            'success' => true,
            'message' => 'Subject Assign Successfully'
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
    public function destroy(Request $request)
    {
        $class = SchoolClass::find($request->class_id);
        $class->subjects()->detach($request->subject_id);

        return response()->json([
            'success' => true,
            'message' => 'Subject Remove Successfully'
        ]);
    }
}
