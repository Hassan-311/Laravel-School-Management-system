<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::all();
        return view('students.index', compact('classes'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function getData()
    {
        $students = Student::with('schoolClass')->select('students.*');
        return DataTables::of($students)
        ->addIndexColumn()
        ->addColumn('class_name', function($student){
            return $student->schoolClass ? $student->schoolClass->name : '-';
        })
        ->addColumn('action', function($student){
            return '<button class="btn btn-sm btn-warning editBtn" data-id="'.$student->id.'">Edit</button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$student->id.'">Delete</button>
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
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:255',
            'roll_no' => 'required|unique:students,roll_no',
            'class_id' => 'required|exists:classes,id',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'roll_no'  => $request->roll_no,
            'class_id' => $request->class_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student Added Successfully',
            'data' => $student
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
        $student = Student::find($id);
        return response()->json($student);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:255',
            'roll_no' => 'required|unique:students,roll_no,' . $id,
            'class_id' => 'required|exists:classes,id'
        ]);

        $student->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Student Updated Succesfully',
            'data' => $student
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student Deleted Successfully'
        ]);
    }
}
