@extends('layouts.backend.app', ['title' => 'Profile Website'])

@section('content')
<x-card title="Profile & Hero Section">
    <form action="{{ route('member.website.profile.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                        value="{{ old('full_name', $profile?->full_name) }}" required>
                    @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>Role / Jabatan</label>
                    <input type="text" name="role_title" class="form-control"
                        placeholder="e.g. Full Stack Developer"
                        value="{{ old('role_title', $profile?->role_title) }}">
                </div>
                <div class="form-group">
                    <label>Short Bio</label>
                    <input type="text" name="short_bio" class="form-control"
                        placeholder="Satu kalimat singkat tentang kamu"
                        value="{{ old('short_bio', $profile?->short_bio) }}">
                </div>
                <div class="form-group">
                    <label>About Me</label>
                    <textarea name="about_me" class="form-control" rows="5"
                        placeholder="Ceritakan dirimu lebih lengkap...">{{ old('about_me', $profile?->about_me) }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Foto Avatar</label>
                    @if($profile?->avatar)
                        <div class="mb-2"><img src="{{ $profile->avatar }}" class="img-thumbnail" style="height:80px"></div>
                    @endif
                    <input type="file" name="avatar" class="form-control-file" accept="image/*">
                    <small class="text-muted">Maks 2MB. JPG/PNG.</small>
                </div>
                <div class="form-group">
                    <label>File CV (PDF)</label>
                    @if($profile?->cv_file)
                        <div class="mb-1"><a href="{{ $profile->cv_file }}" target="_blank" class="btn btn-xs btn-outline-secondary"><i class="fas fa-file-pdf mr-1"></i>Lihat CV</a></div>
                    @endif
                    <input type="file" name="cv_file" class="form-control-file" accept=".pdf">
                    <small class="text-muted">Maks 5MB. PDF saja.</small>
                </div>
                <div class="form-group">
                    <label>Email Kontak</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $profile?->email) }}">
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="phone" class="form-control"
                        value="{{ old('phone', $profile?->phone) }}">
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="location" class="form-control"
                        placeholder="e.g. Jakarta, Indonesia"
                        value="{{ old('location', $profile?->location) }}">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan Profile</button>
        <a href="{{ route('member.website.index') }}" class="btn btn-secondary ml-2">Kembali</a>
    </form>
</x-card>
@endsection
