@extends('admin.layout')

@section('content')
<h2 class="mb-4">Detail Pembayaran - Meja {{ $tableNumber }}</h2>

<div class="mb-3">
    <h5>Pesanan:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @foreach($orders as $order)
                @foreach($order->items as $item)
                    @php
                        $itemSubtotal = $item->quantity * $item->menu->price;
                        $subtotal += $itemSubtotal;
                    @endphp
                    <tr>
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->menu->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

@php
    $tax = $subtotal * 0.11;
    $service = $subtotal * 0.05;
    $total = $subtotal + $tax + $service;
@endphp

<div class="mb-3">
    <p>Subtotal: <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></p>
    <p>Pajak (11%): <strong>Rp {{ number_format($tax, 0, ',', '.') }}</strong></p>
    <p>Biaya Layanan (5%): <strong>Rp {{ number_format($service, 0, ',', '.') }}</strong></p>
    <h4>Total: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></h4>
</div>

<div class="d-flex gap-2">
    <form method="POST" action="{{ route('admin.cashier.pay', ['table_number' => $tableNumber]) }}">
        @csrf
        <button type="submit" class="btn btn-success">Bayar</button>
    </form>
    <a href="{{ route('admin.cashier.receipt', ['table_number' => $tableNumber]) }}" class="btn btn-secondary">Generate Receipt</a>
</div>
@endsection
