<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::all();
        return view('attendance.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getStudents(Request $request)
    {
        $students = Student::where('class_id', $request->class_id)->get();
        return response()->json($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);
        
        Attendance::where('class_id', $request->class_id)->where('date', $request->date)->delete();

        foreach($request->attendance as $student_id => $status){
            Attendance::create([
                'student_id' => $student_id,
                'class_id' => $request->class_id,
                'date' => $request->date,
                'status' => $status,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance Save Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function getReport(Request $request)
    {
        $attendance = Attendance::with('student')->where('class_id', $request->class_id)->where('date', $request->date)->get();

        return response()->json($attendance);
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
