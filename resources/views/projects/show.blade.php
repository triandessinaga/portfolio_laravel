@extends('layouts.app')

@section('title', $project->title)

@section('content')
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Gallery</a></li>
                <li class="breadcrumb-item active">{{ $project->title }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-8">
                <img src="{{ asset('storage/' . $project->image) }}" class="img-fluid rounded" alt="{{ $project->title }}">
            </div>
            <div class="col-md-4">
                <h1>{{ $project->title }}</h1>
                <p class="badge bg-secondary">{{ $project->category }}</p>
                <hr>
                <div class="mb-4">
                    <h4>Description</h4>
                    <p>{{ $project->description }}</p>
                </div>
                <p><small class="text-muted">Created: {{ $project->created_at->format('M d, Y') }}</small></p>
                
                @auth
                    <div class="mt-4">
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                @endauth
                
                <a href="{{ route('projects.index') }}" class="btn btn-outline-dark mt-3">Back to Gallery</a>
            </div>
        </div>
    </div>
@endsection
