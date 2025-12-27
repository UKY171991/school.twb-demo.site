<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code',
            'max_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|min:1',
            'class' => 'required|string|max:255',
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')
                        ->with('success','Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code,' . $subject->id,
            'max_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|min:1',
            'class' => 'required|string|max:255',
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')
                        ->with('success','Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')
                        ->with('success','Subject deleted successfully.');
    }
}
