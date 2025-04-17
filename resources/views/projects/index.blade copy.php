@extends('layouts.app')

@section('title', 'Project Gallery')

@php use Illuminate\Support\Facades\Storage; @endphp

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
      <div class="col-md-4 mb-4">
        <div class="card project-card h-100">
          @if($project->image && Storage::disk('public')->exists($project->image))
            <img
              src="{{ Storage::url($project->image) }}"
              class="card-img-top"
              alt="{{ $project->title }}"
              style="height:200px; object-fit:cover;"
            >
          @else
            <img
              src="{{ asset('images/placeholder.png') }}"
              class="card-img-top"
              alt="No Image"
              style="height:200px; object-fit:cover;"
            >
          @endif

          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $project->title }}</h5>
            <p class="badge bg-secondary">{{ $project->category }}</p>
            <p class="card-text mt-2 flex-fill">{{ Str::limit($project->description, 100) }}</p>
            <a href="{{ route('projects.show', $project) }}" class="btn btn-primary">View Details</a>

            @auth
              <div class="mt-auto">
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form
                  action="{{ route('projects.destroy', $project) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Are you sure?')"
                >
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </div>
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
