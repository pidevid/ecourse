@extends('layouts.backend.app', ['title' => 'Testimonials'])

@section('content')
<div class="row">
    <div class="col-md-5">
        <x-card title="Tambah Testimoni">
            <form action="{{ route('member.website.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Nama Klien <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}" required>
                </div>
                <div class="form-group">
                    <label>Jabatan / Role Klien</label>
                    <input type="text" name="client_role" class="form-control" placeholder="e.g. CEO at Startup X" value="{{ old('client_role') }}">
                </div>
                <div class="form-group">
                    <label>Isi Testimoni <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control" rows="4" required>{{ old('content') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Foto Klien</label>
                    <input type="file" name="avatar" class="form-control-file" accept="image/*">
                    <small class="text-muted">Opsional. Maks 1MB.</small>
                </div>
                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-plus mr-1"></i>Tambah</button>
            </form>
        </x-card>
    </div>
    <div class="col-md-7">
        <x-card title="Daftar Testimoni">
            <table class="table table-sm table-striped">
                <thead>
                    <tr><th>Klien</th><th>Testimoni</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $t)
                    <tr>
                        <td>
                            @if($t->avatar)
                            <img src="{{ asset('storage/website/testimonials/'.$t->avatar) }}" class="img-circle mr-1" style="width:28px;height:28px;object-fit:cover">
                            @endif
                            {{ $t->client_name }}<br><small class="text-muted">{{ $t->client_role }}</small>
                        </td>
                        <td class="small">{{ \Str::limit($t->content, 80) }}</td>
                        <td>
                            <form action="{{ route('member.website.testimonials.destroy', $t) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted">Belum ada testimoni.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
@endsection
