Modul Praktek: Pengembangan Aplikasi Web dengan Laravel, UI/UX, dan Deployment
Studi Kasus: Galeri Portofolio Online
Pengantar
Selamat datang di praktikum pengembangan web dengan Laravel! Pada praktikum ini, kita akan membuat aplikasi Galeri Portofolio Online yang sederhana namun fungsional. Aplikasi ini akan memungkinkan pengguna untuk menampilkan karya-karya mereka dalam format yang menarik. Melalui proyek ini, kita akan belajar tentang Laravel, UI/UX development, dan cara deployment aplikasi ke server.
Tujuan Pembelajaran
•	Memahami instalasi dan konfigurasi Laravel
•	Mengimplementasikan konsep UI/UX dalam pengembangan web
•	Membangun aplikasi web responsif
•	Melakukan deployment aplikasi ke cPanel hosting
Alat dan Bahan
•	Komputer dengan spesifikasi minimal
•	PHP versi 8.0 atau lebih tinggi
•	Composer
•	Web browser (Chrome/Firefox)
•	Text editor (VSCode/Sublime Text/PhpStorm)
•	Akun hosting dengan cPanel
Sesi 1: Persiapan Lingkungan dan Instalasi Laravel
Langkah 1: Instalasi Composer
Composer adalah dependency manager untuk PHP yang akan kita gunakan untuk menginstal Laravel.
1.	Kunjungi getcomposer.org dan download installer Composer
2.	Ikuti instruksi instalasi sesuai sistem operasi Anda
3.	Verifikasi instalasi dengan menjalankan perintah: 
composer --version
Langkah 2: Instalasi Laravel
Sekarang kita akan membuat proyek Laravel baru untuk aplikasi portofolio kita.
1.	Buka terminal/command prompt
2.	Navigasi ke direktori tempat Anda ingin membuat proyek
3.	Jalankan perintah berikut: 
composer create-project laravel/laravel portfolio-app (jika ingin default)
composer create-project laravel/laravel:”11.0” portfolio-app (jika ingin memilih target version yang kita inginkan)
4.	Tunggu proses instalasi selesai (biasanya membutuhkan beberapa menit)
Langkah 3: Menjalankan Server Development
Mari kita pastikan Laravel berjalan dengan baik.
1.	Masuk ke direktori proyek: 
cd portfolio-app
2.	Jalankan server development Laravel: 
php artisan serve
3.	Buka browser dan akses http://localhost:8000
4.	Jika Anda melihat halaman welcome Laravel, berarti instalasi berhasil!
Tips: Biarkan terminal tetap terbuka selama Anda mengembangkan aplikasi. Server development Laravel akan terus berjalan dan memuat perubahan secara otomatis.
Sesi 2: Perencanaan UI/UX dan Database
Langkah 1: Perencanaan UI/UX
Sebelum memulai coding, mari kita rancang UI/UX aplikasi portofolio kita. Aplikasi kita akan memiliki halaman-halaman berikut:
1.	Halaman Beranda - Menampilkan intro dan karya terbaru
2.	Halaman Galeri - Menampilkan semua karya dalam grid
3.	Halaman Detail Karya - Menampilkan informasi lengkap tentang karya tertentu
4.	Halaman Login/Admin - Untuk mengelola karya



Sketsa sederhana untuk halaman beranda:
 
Langkah 2: Perencanaan Database
Kita membutuhkan dua tabel utama:
1.	Tabel users (sudah dibuat otomatis oleh Laravel)
2.	Tabel projects untuk menyimpan karya portofolio
Struktur tabel projects:
•	id (primary key)
•	title (judul karya)
•	description (deskripsi karya)
•	image (path gambar karya)
•	category (kategori: web, grafis, foto, dll)
•	created_at (timestamp)
•	updated_at (timestamp)




Langkah 3: Konfigurasi Database
Mari kita konfigurasi database untuk aplikasi kita.
1.	Buat database baru di MySQL
2.	Buka file .env di root direktori proyek
3.	Ubah konfigurasi database: 
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_db
DB_USERNAME=root
DB_PASSWORD=
(Sesuaikan username dan password dengan konfigurasi lokal Anda)
Langkah 4: Membuat Migration untuk Projects
Sekarang kita akan membuat struktur tabel melalui migration.
1.	Jalankan perintah: 
php artisan make:model project
2.	Buka file migration yang baru dibuat di database/migrations
3.	Edit fungsi up() menjadi: 
public function up()
{
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->string('image');
        $table->string('category');
        $table->timestamps();
    });
}
4.	Jalankan migration: 
php artisan migrate
Selamat! Anda telah berhasil merencanakan UI/UX dan mengkonfigurasi database untuk aplikasi portofolio.
Sesi 3: Membuat Model dan Controller
Langkah 1: Membuat Model Project
Model adalah representasi dari tabel database kita dalam kode.
1.	Jalankan perintah: 
php artisan make:model Project
2.	Buka file app/Models/Project.php
3.	Edit model menjadi: 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 'description', 'image', 'category'
    ];
}
Langkah 2: Membuat Controller
Controller akan menangani logika aplikasi kita.
1.	Jalankan perintah untuk membuat controller: 
php artisan make:controller ProjectController --resource
2.	Buka file app/Http/Controllers/ProjectController.php
3.	Edit controller menjadi: 
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
4.	

Langkah 3: Membuat HomeController
Kita perlu controller terpisah untuk menangani halaman beranda.
1.	Jalankan perintah: 
php artisan make:controller HomeController
2.	Edit file app/Http/Controllers/HomeController.php: 
<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $featuredProjects = Project::latest()->take(3)->get();
        return view('home', compact('featuredProjects'));
    }

}



Membuat Autentikasi
Jalankan perintah untuk membuat fitur autentikasi:
Langkah 1: Instalasi Bootstrap
Kita akan menggunakan Bootstrap untuk mempercepat proses desain UI.
1.	Jalankan perintah: 
composer require laravel/ui
php artisan ui bootstrap
npm install
npm run dev

Menambahkan Routes
Buka file routes/web.php dan update kode route berikut:
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;

// Halaman welcome
Route::get('/', function () {
    return view('welcome');
});

// Autentikasi
Auth::routes();

// Setelah  login, redirect ke  HomeController@index
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Dashboard (bisa di akses jika sudah login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Resource routes untuk ProjectController
// index & show = publik
// create/store/edit/update/destroy = membutuhkan auth (karena middleware di controller)
Route::resource('projects', ProjectController::class);

Langkah 2: Membuat Layout Utama
Buat file resources/views/layouts/app.blade.php (jika belum ada):
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Gallery - @yield('title', 'Welcome')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        /* Menjamin konten penuh dan footer tetap di bawah */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .hero-section {
            background-color: #f8f9fa;
            padding: 100px 0;
            margin-bottom: 40px;
        }
        
        .project-card {
            transition: transform 0.3s;
            margin-bottom: 30px;
        }
        
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: auto; /* Pastikan footer di bawah */
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Portfolio Gallery</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.index') }}">Gallery</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <!-- <a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a> -->
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Portfolio Gallery. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

Langkah 3: Membuat Halaman Beranda
Buat file resources/views/home.blade.php:
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


Langkah 4: Membuat Halaman Galeri Project
Buat file resources/views/projects/index.blade.php:
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

Langkah 5: Membuat Halaman Detail Project
Buat file resources/views/projects/show.blade.php:
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

Langkah 6: Membuat Form Tambah Project
Buat file resources/views/projects/create.blade.php:
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

Langkah 7: Membuat Form Edit Project
Buat file resources/views/projects/edit.blade.php:
@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <div class="container mt-5">
        <h1>Edit Project</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}" required>
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Web Design" {{ old('category', $project->category) == 'Web Design' ? 'selected' : '' }}>Web Design</option>
                    <option value="Graphic Design" {{ old('category', $project->category) == 'Graphic Design' ? 'selected' : '' }}>Graphic Design</option>
                    <option value="Photography" {{ old('category', $project->category) == 'Photography' ? 'selected' : '' }}>Photography</option>
                    <option value="UI/UX" {{ old('category', $project->category) == 'UI/UX' ? 'selected' : '' }}>UI/UX</option>
                    <option value="Illustration" {{ old('category', $project->category) == 'Illustration' ? 'selected' : '' }}>Illustration</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $project->description) }}</textarea>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Project Image</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*">
                <div class="form-text">Leave empty to keep current image</div>
                
                @if ($project->image)
                    <div class="mt-2">
                        <p>Current Image:</p>
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" class="img-thumbnail" style="max-height: 200px">
                    </div>
                @endif
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

Langkah 8: Membuat Symbolic Link untuk Storage
Laravel menggunakan symbolic link untuk mengakses file yang disimpan di direktori storage. Jalankan perintah berikut:
php artisan storage:link

