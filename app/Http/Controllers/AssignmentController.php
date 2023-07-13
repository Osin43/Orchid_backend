<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::all();
        return response()->json(['assignments' => $assignments]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs('public/files', $file, $fileName);

        $assignment = new Assignment();
        $assignment->title = $request->input('title');
        $assignment->description = $request->input('description');
        $assignment->file = $fileName;
        $assignment->save();

        return response()->json(['message' => 'Assignment created successfully', 'assignment' => $assignment]);
    }

    public function show($id)
    {
        $assignment = Assignment::findOrFail($id);
        $fileUrl = Storage::url('public/files/' . $assignment->file);

        return response()->json(['assignment' => $assignment, 'file_url' => $fileUrl]);
    }

    public function update(Request $request, $id)
    {
        // Implementation for updating an assignment
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        Storage::delete('public/files/' . $assignment->file);
        $assignment->delete();

        return response()->json(['message' => 'Assignment deleted successfully']);
    }

    public function upload(Request $request, $id)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Find the assignment by ID
        $assignment = Assignment::findOrFail($id);

        // Update the assignment status to "uploaded"
        $assignment->status = 'uploaded';
        $assignment->save();

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();

            // Move the uploaded file to the storage directory
            $file->storeAs('assignments', $filename);
        }

        return response()->json(['message' => 'Assignment uploaded successfully']);
    }
}
