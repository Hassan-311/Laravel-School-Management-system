<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TeacherSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();
        return view('teacher_subject.index', compact('teachers', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getData()
    {
        $teachers = Teacher::with('subjects')->select('teachers.*');
        return DataTables::of($teachers)
        ->addIndexColumn()
        ->addColumn('subjects', function($teacher){
            if($teacher->subjects->isEmpty()) return '-';
            return $teacher->subjects->map(function($s){
                return '<span class="badge bg-primary me-1">'.$s->name.'</span>
                        <button class="btn btn-sm btn-danger removeBtn" 
                            data-teacher="'.$s->pivot->teacher_id.'"
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
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $teacher = Teacher::find($request->teacher_id);

        if($teacher->subjects->contains($request->subject_id)){
            return response()->json([
                'success' => false,
                'message' => 'This subject has already been assigned',
            ]);
        }

        $teacher->subjects()->attach($request->subject_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Subject assign Successfully',
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
        $teacher = Teacher::find($request->teacher_id);
        $teacher->subjects()->detach($request->subject_id);

        return response()->json([
            'success' => true,
            'messsage' => 'Subject Remove Successfully',
        ]);
    }
}
