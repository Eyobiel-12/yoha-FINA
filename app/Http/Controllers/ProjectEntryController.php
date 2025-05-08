<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectEntry;
use Illuminate\Http\Request;

class ProjectEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show form for creating a new entry for a specific project.
     */
    public function createForProject(Project $project)
    {
        return view('project-entries.create', compact('project'));
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
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'entry_date' => 'required|date',
            'hours' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $entry = ProjectEntry::create($validated);

        return redirect()->route('projects.show', $entry->project_id)
            ->with('success', 'Time entry added successfully.');
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
    public function edit(ProjectEntry $projectEntry)
    {
        $project = $projectEntry->project;
        
        return view('project-entries.edit', compact('projectEntry', 'project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectEntry $projectEntry)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'hours' => 'required|numeric|min:0.01',
            'rate' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $projectEntry->update($validated);

        return redirect()->route('projects.show', $projectEntry->project_id)
            ->with('success', 'Time entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
