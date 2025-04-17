<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Hanya index & show yang bebas diakses
        $this->middleware('auth')->except(['index', 'show']);
    }

    /** Display a listing of the resource. */
    public function index()
    {
        $projects = Project::latest()->get();
        return view('projects.index', compact('projects'));
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('projects.create');
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
{
    $data = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required',
        'category'    => 'required|string|max:50',
        'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Simpan ke disk 'public' -> storage/app/public/projects/...
    $data['image'] = $request->file('image')->store('projects','public');

    Project::create($data);

    return redirect()->route('projects.index')
                     ->with('success', 'Project created successfully.');
}

public function update(Request $request, Project $project)
{
    $data = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required',
        'category'    => 'required|string|max:50',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        // Hapus file lama jika ada
        if (Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }
        // Simpan file baru
        $data['image'] = $request->file('image')->store('projects','public');
    }

    $project->update($data);

    return redirect()->route('projects.index')
                     ->with('success', 'Project updated successfully.');
}

    /** Display the specified resource. */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /** Show the form for editing the specified resource. */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /** Update the specified resource in storage. */
    

    /** Remove the specified resource from storage. */
    public function destroy(Project $project)
    {
        Storage::delete('public/'.$project->image);
        $project->delete();

        return redirect()->route('projects.index')
                         ->with('success', 'Project deleted successfully.');
    }
}
