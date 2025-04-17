@extends('layouts.app')

@section('title', 'Add New Project')

@section('content')
    <div class="container mt-5">
        <h1>Tambah Projek Baru</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Web Design" {{ old('category') == 'Web Design' ? 'selected' : '' }}>Web Design</option>
                    <option value="Graphic Design" {{ old('category') == 'Graphic Design' ? 'selected' : '' }}>Graphic Design</option>
                    <option value="Photography" {{ old('category') == 'Photography' ? 'selected' : '' }}>Photography</option>
                    <option value="UI/UX" {{ old('category') == 'UI/UX' ? 'selected' : '' }}>UI/UX</option>
                    <option value="Illustration" {{ old('category') == 'Illustration' ? 'selected' : '' }}>Illustration</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Project Image</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
                <div class="form-text">Upload an image of your project (JPEG, PNG, GIF, max 2MB)</div>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-success">Save Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
