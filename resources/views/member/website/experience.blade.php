@extends('layouts.backend.app', ['title' => 'Experience'])

@section('content')
<div class="row">
    <div class="col-md-5">
        <x-card title="Tambah Pengalaman">
            <form action="{{ route('member.website.experience.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Perusahaan <span class="text-danger">*</span></label>
                    <input type="text" name="company" class="form-control" value="{{ old('company') }}" required>
                </div>
                <div class="form-group">
                    <label>Posisi <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-6">
                        <label>Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>
                    <div class="form-group col-6">
                        <label>Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_current" name="is_current" {{ old('is_current') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_current">Masih bekerja di sini</label>
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
        <x-card title="Riwayat Pengalaman">
            <table class="table table-sm table-striped">
                <thead>
                    <tr><th>Perusahaan</th><th>Posisi</th><th>Periode</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($experiences as $exp)
                    <tr>
                        <td>{{ $exp->company }}</td>
                        <td>{{ $exp->position }}</td>
                        <td class="small">
                            {{ $exp->start_date->format('M Y') }} —
                            {{ $exp->is_current ? 'Sekarang' : ($exp->end_date ? $exp->end_date->format('M Y') : '-') }}
                        </td>
                        <td>
                            <form action="{{ route('member.website.experience.destroy', $exp) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada pengalaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
@endsection
