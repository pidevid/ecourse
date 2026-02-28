@extends('layouts.backend.app', ['title' => 'Beli Akses Personal Website'])

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-teal shadow">
            <div class="card-header text-center">
                <h3 class="card-title font-weight-bold"><i class="fas fa-globe mr-2"></i>Personal Website Generator</h3>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-globe fa-5x text-teal mb-3"></i>
                    <h4 class="font-weight-bold">Buat Personal Website Portofoliomu!</h4>
                    <p class="text-muted">Tampilkan skill, pengalaman, dan portfolio dalam satu halaman profesional dengan URL unik milikmu sendiri.</p>
                </div>

                <div class="row mb-4">
                    <div class="col-4 text-center">
                        <i class="fas fa-paint-brush fa-2x text-primary mb-2"></i>
                        <p class="font-weight-bold mb-0">3 Tema</p>
                        <small class="text-muted">Minimal, Creative, Professional</small>
                    </div>
                    <div class="col-4 text-center">
                        <i class="fas fa-link fa-2x text-success mb-2"></i>
                        <p class="font-weight-bold mb-0">URL Unik</p>
                        <small class="text-muted">/portfolio/{{ auth()->user()->username }}</small>
                    </div>
                    <div class="col-4 text-center">
                        <i class="fas fa-infinity fa-2x text-info mb-2"></i>
                        <p class="font-weight-bold mb-0">Selamanya</p>
                        <small class="text-muted">Bayar sekali, akses permanent</small>
                    </div>
                </div>

                <div class="alert alert-teal mb-4">
                    <h4 class="font-weight-bold mb-0">Rp 100.000 <small class="text-muted font-weight-normal">/ selamanya</small></h4>
                    <small>Admin & Author mendapatkan akses gratis</small>
                </div>

                <form action="{{ route('member.website.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-teal btn-lg btn-block">
                        <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang — Rp 100.000
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
