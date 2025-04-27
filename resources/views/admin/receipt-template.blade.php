<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Pembayaran - Kafe Nilu</title>
  <style>
    body {
      font-family: monospace;
      width: 300px;
      margin: 0 auto;
    }
    .center {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    td, th {
      font-size: 14px;
      padding: 4px 0;
    }
    .total {
      font-weight: bold;
    }
    .line {
      border-top: 1px dashed #000;
      margin: 10px 0;
    }
  </style>
</head>
<body>

  <div class="center">
    <h3>KAFE NILU</h3>
    <p>Jl. Sorowajan Baru, Jomblangan, Banguntapan, Bantul, Yogyakarta<br>
    Telp: 0857 4318 5987| IG: @nilu_sorowajan</p>
    <p><strong>STRUK PEMBAYARAN</strong></p>
  </div>

  <p>
    Invoice: {{ $receipt->invoice_number }}<br>
    Tanggal: {{ \Carbon\Carbon::parse($receipt->paid_at)->format('d M Y - H:i') }}<br>
    Meja: {{ $receipt->table_number }}<br>
    Kasir: {{ $receipt->cashier_name }}<br>
  </p>

  <div class="line"></div>

  <table>
    @foreach ($orders as $order)
      @foreach ($order->items as $item)
        <tr>
          <td>{{ $item->quantity }}x {{ $item->menu->name }}</td>
          <td align="right">Rp{{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    @endforeach

    <tr><td>Subtotal</td><td align="right">Rp{{ number_format($receipt->total_price, 0, ',', '.') }}</td></tr>
    <tr><td>Pajak (10%)</td><td align="right">Rp{{ number_format($receipt->tax_amount, 0, ',', '.') }}</td></tr>
    <tr><td>Biaya Layanan (5%)</td><td align="right">Rp{{ number_format($receipt->service_charge, 0, ',', '.') }}</td></tr>
    <tr class="total"><td>Total</td><td align="right">Rp{{ number_format($receipt->grand_total, 0, ',', '.') }}</td></tr>
  </table>

  <div class="line"></div>

  <p>
    {{-- Catatan, kalau mau dibuat dinamis dari order_items->note --}}
  </p>

  <div class="center">
    <p>Terima kasih atas kunjungan Anda!<br>
  </div>


  <script>
    window.onload = function() {
        window.print();
    };
  </script>
</body>
</html>
