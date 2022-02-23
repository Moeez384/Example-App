<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    public function index()
    {
        $students = Student::all();
        return view('dashborad', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'phone_number' => 'required|digits:11'
        ]);
        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => 'pending',
            'approved_by' => Auth::user()->name,
        ]);
        $messages['success'] = 'Data Inserted Successfully';
        return redirect()->route('students.index')->with('messages', $messages);
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|digits:11',
            'status' => 'required',
            'email' => 'required|string|email',
        ]);
        $student->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'email' => $request->email,
        ]);
        $messages['success'] = 'Data Updated Successfully';
        return redirect()->route('students.index')->with('messages', $messages);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        $messages['danger'] = 'Deleted Successfully';
        return redirect()->route('students.index')->with('messages', $messages);
    }

    public function changeStatus(Request $request)
    {
        Student::where('id', $request->id)->update([
            'status' => $request->status,
        ]);
        $student = Student::where('id', $request->id)->first();
        return response()->json($student);
    }

    public function hello()
    {
        return "Just returing Hello";
    }

    public function test()
    {
        return "Test from Main";
    }
}
