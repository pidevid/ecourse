@extends('layouts.backend.app', ['title' => 'Portfolio'])

@section('content')
<x-card title="Tambah Portfolio">
    <form action="{{ route('member.website.portfolio.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Judul Proyek <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label>URL Proyek</label>
                    <input type="url" name="url" class="form-control" placeholder="https://..." value="{{ old('url') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tech Stack</label>
                    <input type="text" name="tech_stack" class="form-control" placeholder="e.g. Laravel, Vue.js, MySQL" value="{{ old('tech_stack') }}">
                </div>
                <div class="form-group">
                    <label>Gambar Preview</label>
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                    <small class="text-muted">Maks 3MB.</small>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-plus mr-1"></i>Tambah Portfolio</button>
    </form>
</x-card>

<div class="row mt-3">
    @forelse($portfolios as $item)
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            @if($item->image)
            <img src="{{ asset('storage/website/portfolio/' . $item->image) }}" class="card-img-top" style="height:160px;object-fit:cover">
            @endif
            <div class="card-body">
                <h6 class="font-weight-bold">{{ $item->title }}</h6>
                <p class="text-muted small">{{ \Str::limit($item->description, 80) }}</p>
                @if($item->tech_stack)
                <p class="small"><i class="fas fa-code mr-1 text-info"></i>{{ $item->tech_stack }}</p>
                @endif
                @if($item->url)
                <a href="{{ $item->url }}" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
                @endif
            </div>
            <div class="card-footer p-2">
                <form action="{{ route('member.website.portfolio.destroy', $item) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-xs" onclick="return confirm('Hapus portfolio ini?')"><i class="fas fa-trash mr-1"></i>Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center text-muted py-4">Belum ada portfolio.</div>
    @endforelse
</div>
@endsection
