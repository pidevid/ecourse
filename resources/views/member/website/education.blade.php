@extends('layouts.backend.app', ['title' => 'Education'])

@section('content')
<div class="row">
    <div class="col-md-5">
        <x-card title="Tambah Pendidikan">
            <form action="{{ route('member.website.education.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Institusi <span class="text-danger">*</span></label>
                    <input type="text" name="institution" class="form-control" value="{{ old('institution') }}" required>
                </div>
                <div class="form-group">
                    <label>Gelar <span class="text-danger">*</span></label>
                    <input type="text" name="degree" class="form-control" placeholder="e.g. S1, S2, SMA" value="{{ old('degree') }}" required>
                </div>
                <div class="form-group">
                    <label>Jurusan <span class="text-danger">*</span></label>
                    <input type="text" name="field" class="form-control" placeholder="e.g. Teknik Informatika" value="{{ old('field') }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-6">
                        <label>Tahun Masuk <span class="text-danger">*</span></label>
                        <input type="number" name="start_year" class="form-control" min="1970" max="2099" value="{{ old('start_year') }}" required>
                    </div>
                    <div class="form-group col-6">
                        <label>Tahun Lulus</label>
                        <input type="number" name="end_year" class="form-control" min="1970" max="2099" value="{{ old('end_year') }}">
                        <small class="text-muted">Kosongkan jika belum lulus</small>
                    </div>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-plus mr-1"></i>Tambah</button>
            </form>
        </x-card>
    </div>
    <div class="col-md-7">
        <x-card title="Riwayat Pendidikan">
            <table class="table table-sm table-striped">
                <thead>
                    <tr><th>Institusi</th><th>Gelar</th><th>Periode</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($educations as $edu)
                    <tr>
                        <td>{{ $edu->institution }}</td>
                        <td>{{ $edu->degree }} - {{ $edu->field }}</td>
                        <td class="small">{{ $edu->start_year }} — {{ $edu->end_year ?? 'Sekarang' }}</td>
                        <td>
                            <form action="{{ route('member.website.education.destroy', $edu) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada pendidikan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
@endsection
