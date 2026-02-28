@extends('layouts.backend.app', ['title' => 'Social Links'])

@section('content')
<x-card title="Social Media Links">
    <form action="{{ route('member.website.social.save') }}" method="POST">
        @csrf
        @foreach([
            ['platform' => 'linkedin',  'icon' => 'fab fa-linkedin',  'label' => 'LinkedIn',  'placeholder' => 'https://linkedin.com/in/username'],
            ['platform' => 'github',    'icon' => 'fab fa-github',    'label' => 'GitHub',    'placeholder' => 'https://github.com/username'],
            ['platform' => 'instagram', 'icon' => 'fab fa-instagram', 'label' => 'Instagram', 'placeholder' => 'https://instagram.com/username'],
            ['platform' => 'twitter',   'icon' => 'fab fa-twitter',   'label' => 'Twitter/X', 'placeholder' => 'https://twitter.com/username'],
            ['platform' => 'dribbble',  'icon' => 'fab fa-dribbble',  'label' => 'Dribbble',  'placeholder' => 'https://dribbble.com/username'],
            ['platform' => 'behance',   'icon' => 'fab fa-behance',   'label' => 'Behance',   'placeholder' => 'https://behance.net/username'],
            ['platform' => 'website',   'icon' => 'fas fa-globe',     'label' => 'Website',   'placeholder' => 'https://yourwebsite.com'],
        ] as $social)
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">
                <i class="{{ $social['icon'] }} fa-lg mr-1"></i> {{ $social['label'] }}
            </label>
            <div class="col-sm-10">
                <input type="url" name="{{ $social['platform'] }}" class="form-control"
                    placeholder="{{ $social['placeholder'] }}"
                    value="{{ old($social['platform'], $links->get($social['platform'])?->url) }}">
                @error($social['platform']) <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        @endforeach
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan Social Links</button>
        <a href="{{ route('member.website.index') }}" class="btn btn-secondary ml-2">Kembali</a>
    </form>
</x-card>
@endsection
