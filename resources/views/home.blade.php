@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Welcome to My Portfolio</h1>
            <p class="lead">Showcasing creative works and projects</p>
        </div>
    </section>

    <!-- Featured Projects -->
    <section class="container">
        <h2 class="mb-4">Featured Works</h2>
        
        <div class="row">
            @forelse ($featuredProjects as $project)
                <div class="col-md-4">
                    <div class="card project-card">
                        <img src="{{ asset('storage/' . $project->image) }}" class="card-img-top" alt="{{ $project->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $project->title }}</h5>
                            <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No featured projects yet.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-dark">View All Works</a>
        </div>
    </section>
@endsection
