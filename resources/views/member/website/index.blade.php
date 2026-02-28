@extends('layouts.backend.app', ['title' => 'Personal Website'])

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="card card-outline card-teal">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold"><i class="fas fa-globe mr-2"></i>Personal Website Dashboard</h3>
                <div>
                    <a href="{{ route('portfolio.show', auth()->user()->username) }}" target="_blank" class="btn btn-sm btn-outline-teal">
                        <i class="fas fa-external-link-alt mr-1"></i> Preview Website
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>URL:</strong>
                            <a href="{{ route('portfolio.show', auth()->user()->username) }}" target="_blank">
                                {{ url('/portfolio/'.auth()->user()->username) }}
                            </a>
                        </p>
                        <p><strong>Tema:</strong> <span class="badge badge-info">{{ ucfirst($website->theme) }}</span></p>
                        <p><strong>Status:</strong>
                            @if($website->is_published)
                                <span class="badge badge-success"><i class="fas fa-eye mr-1"></i>Published</span>
                            @else
                                <span class="badge badge-secondary"><i class="fas fa-eye-slash mr-1"></i>Draft</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Profil:</strong> {{ $website->profile ? '<span class="badge badge-success">Terisi</span>' : '<span class="badge badge-warning">Belum diisi</span>' }}</p>
                        <p><strong>Skills:</strong> <span class="badge badge-info">{{ $website->skills->count() }} item</span></p>
                        <p><strong>Portfolio:</strong> <span class="badge badge-info">{{ $website->portfolios->count() }} item</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach([
        ['route' => 'member.website.profile', 'icon' => 'fas fa-user', 'label' => 'Profile', 'color' => 'primary', 'desc' => 'Nama, bio, foto, CV'],
        ['route' => 'member.website.skills', 'icon' => 'fas fa-code', 'label' => 'Skills', 'color' => 'success', 'desc' => 'Keahlian & persentase'],
        ['route' => 'member.website.services', 'icon' => 'fas fa-concierge-bell', 'label' => 'Services', 'color' => 'warning', 'desc' => 'Layanan yang ditawarkan'],
        ['route' => 'member.website.experience', 'icon' => 'fas fa-briefcase', 'label' => 'Experience', 'color' => 'info', 'desc' => 'Riwayat pekerjaan'],
        ['route' => 'member.website.education', 'icon' => 'fas fa-graduation-cap', 'label' => 'Education', 'color' => 'secondary', 'desc' => 'Riwayat pendidikan'],
        ['route' => 'member.website.portfolio', 'icon' => 'fas fa-images', 'label' => 'Portfolio', 'color' => 'danger', 'desc' => 'Karya & proyek'],
        ['route' => 'member.website.testimonials', 'icon' => 'fas fa-quote-left', 'label' => 'Testimonials', 'color' => 'primary', 'desc' => 'Testimoni klien'],
        ['route' => 'member.website.social', 'icon' => 'fas fa-share-alt', 'label' => 'Social Links', 'color' => 'success', 'desc' => 'LinkedIn, GitHub, dll.'],
        ['route' => 'member.website.settings', 'icon' => 'fas fa-cog', 'label' => 'Settings', 'color' => 'dark', 'desc' => 'Tema, warna, font, SEO'],
    ] as $menu)
    <div class="col-md-4 col-sm-6 mb-3">
        <a href="{{ route($menu['route']) }}" class="btn btn-{{ $menu['color'] }} btn-block text-left p-3">
            <i class="{{ $menu['icon'] }} fa-lg mr-2"></i>
            <strong>{{ $menu['label'] }}</strong>
            <br><small class="ml-4 opacity-75">{{ $menu['desc'] }}</small>
        </a>
    </div>
    @endforeach
</div>
@endsection
