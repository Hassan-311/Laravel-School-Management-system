<?php

namespace App\Http\Controllers;

use App\Models\fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('fees.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getData()
    {
        $fees = Fee::with('student')->select('fees.*');
        return DataTables::of($fees)
        ->addIndexColumn()
        ->addColumn('student_name', function($fee){
            return $fee->student ? $fee->student->name : '-';
        })
        ->addColumn('status', function($fee){
            return $fee->status === 'paid'
            ? '<span class="badge bg-success">Paid</span>'
            : '<span class="badge bg-danger">Unpaid</span>';
        })
        ->addColumn('action', function($fee){
            return '<button class="btn btn-sm btn-warning editBtn" data-id="'.$fee->id.'">Edit</button>
                <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$fee->id.'">Delete</button>
            ';
        })
        ->filterColumn('student_name', function($query, $keyword){
            $query->whereHas('student', function($q) use ($keyword){
                $q->where('name', 'like', "%{$keyword}%");
            });
        })

        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|integer',
            'month' => 'required|string',
            'year' => 'required|integer',
            'status' => 'required|in:paid,unpaid',
        ]);

        $fee = Fee::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Fee Added Successfully',
            'data' => $fee
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
        $fee = Fee::find($id);
        return response()->json($fee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fee = Fee::find($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|integer',
            'month' => 'required|string',
            'year' => 'required|integer',
            'status' => 'required|in:paid,unpaid',
        ]);

        $fee->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Fees Update Successfully',
            'data' => $fee
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fee = Fee::find($id);
        $fee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fee Delete Successfully'
        ]);
    }
}
