@extends('user.layout')

@section('content')
<div class="px-3 pt-3">
    <h4 class="mb-3">Pesanan Anda</h4>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <p>Anda belum memiliki pesanan.</p>
    @else
        <div class="list-group">
            @php $grandTotal = 0; @endphp
            @foreach($cartItems as $item)
                @php
                    $totalPrice = $item->quantity * $item->menu->price;
                    $grandTotal += $totalPrice;
                @endphp
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->menu->name }}</strong><br>
                            <small>{{ $item->quantity }} x Rp {{ number_format($item->menu->price, 0, ',', '.') }}</small>
                            <br>
                            <small>{{ $item->note }}</small>
                        </div>
                        <div class="text-end">
                            <strong>Rp {{ number_format($totalPrice, 0, ',', '.') }}</strong>
                            <form action="{{ route('user.cart.delete', ['token' => $token]) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                @csrf
                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">✕</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <strong>Total</strong>
            <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
        </div>
        <small>Harga sebelum pajak</small>

        <div class="mt-4">
            <form action="{{ route('user.order.create', ['token' => $token]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success w-100">Lanjutkan Pemesanan</button>
            </form>
        </div>
    @endif
</div>

<script>
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>
@endsection
