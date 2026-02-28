@extends('layouts.backend.app', ['title' => 'Skills'])

@section('content')
<div class="row">
    <div class="col-md-5">
        <x-card title="Tambah Skill">
            <form action="{{ route('member.website.skills.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Skill <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        placeholder="e.g. Laravel, React, Figma" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control">
                        <option value="beginner" {{ old('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', 'intermediate') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="expert" {{ old('level') === 'expert' ? 'selected' : '' }}>Expert</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Persentase (0-100)</label>
                    <input type="number" name="percentage" class="form-control" min="0" max="100" value="{{ old('percentage', 80) }}">
                </div>
                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-plus mr-1"></i>Tambah</button>
            </form>
        </x-card>
    </div>
    <div class="col-md-7">
        <x-card title="Daftar Skills">
            <table class="table table-sm table-striped">
                <thead>
                    <tr><th>Nama</th><th>Level</th><th>%</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($skills as $skill)
                    <tr>
                        <td>{{ $skill->name }}</td>
                        <td><span class="badge badge-{{ $skill->level === 'expert' ? 'success' : ($skill->level === 'intermediate' ? 'info' : 'secondary') }}">{{ ucfirst($skill->level) }}</span></td>
                        <td>{{ $skill->percentage }}%</td>
                        <td>
                            <form action="{{ route('member.website.skills.destroy', $skill) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs" onclick="return confirm('Hapus skill ini?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada skill.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
@endsection
