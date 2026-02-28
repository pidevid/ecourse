@extends('layouts.backend.app', ['title' => 'Settings Website'])

@section('content')
<x-card title="Pengaturan Website">
    <form action="{{ route('member.website.settings.save') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tema <span class="text-danger">*</span></label>
                    <select name="theme" class="form-control">
                        <option value="minimal" {{ $website->theme === 'minimal' ? 'selected' : '' }}>Minimal — Bersih & Elegan</option>
                        <option value="creative" {{ $website->theme === 'creative' ? 'selected' : '' }}>Creative — Dark & Modern</option>
                        <option value="professional" {{ $website->theme === 'professional' ? 'selected' : '' }}>Professional — Corporate</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Warna Aksen</label>
                    <div class="d-flex align-items-center">
                        <input type="color" name="accent_color" class="form-control mr-2" style="width:60px;height:38px;padding:2px"
                            value="{{ $website->accent_color }}">
                        <small class="text-muted">Warna utama tombol & highlight</small>
                    </div>
                </div>
                <div class="form-group">
                    <label>Font Family</label>
                    <select name="font_family" class="form-control">
                        <option value="sans" {{ $website->font_family === 'sans' ? 'selected' : '' }}>Sans (Modern)</option>
                        <option value="serif" {{ $website->font_family === 'serif' ? 'selected' : '' }}>Serif (Classic)</option>
                        <option value="mono" {{ $website->font_family === 'mono' ? 'selected' : '' }}>Mono (Tech)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Meta Title (SEO)</label>
                    <input type="text" name="meta_title" class="form-control"
                        placeholder="Judul tab browser"
                        value="{{ old('meta_title', $website->meta_title) }}">
                </div>
                <div class="form-group">
                    <label>Meta Description (SEO)</label>
                    <textarea name="meta_description" class="form-control" rows="3"
                        placeholder="Deskripsi untuk mesin pencari">{{ old('meta_description', $website->meta_description) }}</textarea>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_published" name="is_published"
                            {{ $website->is_published ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_published">Publikasikan Website</label>
                    </div>
                    <small class="text-muted">Jika dinonaktifkan, website tidak bisa diakses publik (404).</small>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan Pengaturan</button>
        <a href="{{ route('member.website.index') }}" class="btn btn-secondary ml-2">Kembali</a>
    </form>
</x-card>
@endsection
