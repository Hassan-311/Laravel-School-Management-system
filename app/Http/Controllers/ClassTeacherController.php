<?php

namespace App\Http\Controllers;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassTeacherController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::all();
        $teachers = Teacher::all();
        return view('class_teacher.index', compact('classes', 'teachers'));
    }

    public function getData()
    {
        $classes = SchoolClass::with('teachers')->select('classes.*');
        return DataTables::of($classes)
        ->addIndexColumn()
        ->addColumn('teachers', function($class){
            if($class->teachers->isEmpty()) return '-';
            return $class->teachers->map(function($t){
                return '<span class="badge bg-primary me-1">'.$t->name.'</span>
                        <button class="btn btn-sm btn-danger removeBtn"
                            data-class="'.$t->pivot->class_id.'"
                            data-teacher="'.$t->pivot->teacher_id.'">x</button>';
            })->implode(' ');
        })
        ->rawColumns(['teachers'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $class = SchoolClass::find($request->class_id);

        if($class->teachers->contains($request->teacher_id)){
            return response()->json([
                'success' => false,
                'message' => 'This teacher is already assigned',
            ]);
        }

        $class->teachers()->attach($request->teacher_id);

        return response()->json([
            'success' => true,
            'message' => 'Teacher Assign Successfully',
        ]);
    }

    public function destroy(Request $request)
    {
        $class = SchoolClass::find($request->class_id);
        $class->teachers()->detach($request->teacher_id);

        return response()->json([
            'success' => true,
            'message' => 'Teacher remove successfully',
        ]);
    }
}
