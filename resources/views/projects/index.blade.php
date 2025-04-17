@extends('layouts.app')

@section('title', 'Project Gallery')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Project Gallery</h1>
            @auth
                <a href="{{ route('projects.create') }}" class="btn btn-primary">Add New Project</a>
            @endauth
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            @forelse ($projects as $project)
                <div class="col-md-4">
                    <div class="card project-card">
                        <img src="{{ asset('storage/' . $project->image) }}" class="card-img-top" alt="{{ $project->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $project->title }}</h5>
                            <p class="badge bg-secondary">{{ $project->category }}</p>
                            <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-primary">View Details</a>
                            
                            @auth
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h3>No projects found</h3>
                    <p>Be the first to add a project!</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
