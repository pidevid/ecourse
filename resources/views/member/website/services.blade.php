@extends('layouts.backend.app', ['title' => 'Services'])

@section('content')
<div class="row">
    <div class="col-md-5">
        <x-card title="Tambah Layanan">
            <form action="{{ route('member.website.services.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Judul Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Web Development" value="{{ old('title') }}" required>
                </div>
                <div class="form-group">
                    <label>Icon (Font Awesome class)</label>
                    <input type="text" name="icon" class="form-control" placeholder="e.g. fas fa-laptop-code" value="{{ old('icon') }}">
                    <small class="text-muted">Opsional. Contoh: <code>fas fa-code</code></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-plus mr-1"></i>Tambah</button>
            </form>
        </x-card>
    </div>
    <div class="col-md-7">
        <x-card title="Daftar Layanan">
            <table class="table table-sm table-striped">
                <thead>
                    <tr><th>Icon</th><th>Judul</th><th>Deskripsi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td><i class="{{ $service->icon ?? 'fas fa-star' }}"></i></td>
                        <td>{{ $service->title }}</td>
                        <td class="text-muted small">{{ \Str::limit($service->description, 60) }}</td>
                        <td>
                            <form action="{{ route('member.website.services.destroy', $service) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" onclick="return confirm('Hapus layanan ini?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada layanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
@endsection
