@extends('layouts.backend.app', ['title' => 'Checkout Personal Website'])

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card card-outline card-teal shadow">
            <div class="card-body py-5">
                <i class="fas fa-wallet fa-4x text-teal mb-4"></i>
                <h4 class="font-weight-bold">Selesaikan Pembayaran</h4>
                <p class="text-muted">Pilih metode pembayaran yang Anda inginkan melalui Midtrans.</p>
                <h3 class="text-teal font-weight-bold mb-4">Rp 100.000</h3>
                <button id="pay-button" class="btn btn-teal btn-lg">
                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript" src="{{ config('services.midtrans.snap_url') }}"
    data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function () { window.location.href = "{{ route('member.website.index') }}"; },
            onPending: function () { window.location.href = "{{ route('member.website.buy') }}"; },
            onError:   function () { window.location.href = "{{ route('member.website.buy') }}"; },
        });
    });
</script>
@endpush
