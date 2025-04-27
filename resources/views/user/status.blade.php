@extends('user.layout')

@section('content')
<div class="px-3 pt-3">
    <h4 class="mb-3">Status Pesanan</h4>

    @if($orders->isEmpty())
        <p>Tidak ada pesanan untuk meja ini.</p>
    @else
        @foreach($orders as $order)
            <div class="mb-3 rounded p-3" style="background-color: #EFEFEF; color: black;">
                <h5>Antrian #{{ $order->queue_number }} dari {{ $maxQueue }}</h5>
                <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
                <ul class="mb-0">
                    @foreach($order->items as $item)
                        <li>{{ $item->menu->name }} x {{ $item->quantity }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endif
</div>
@endsection
