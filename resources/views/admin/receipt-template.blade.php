<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Pembayaran - Kafe Nilu</title>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
      width: 300px;
      margin: 0 auto;
    }
    .center {
      text-align: center;
    }
    .header {
      margin-bottom: 10px;
    }
    .header img {
      width: 50px;
      height: 50px;
    }
    .header p {
      font-size: 12px;
      margin: 0;
    }
    .line {
      border-top: 1px dashed #000;
      margin: 5px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }
    td, th {
      padding: 4px 0;
      text-align: left;
    }
    .total {
      font-weight: bold;
    }
    .amount {
      text-align: right;
    }
    .footer {
      text-align: center;
      font-size: 10px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <div class="center header">
    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Cat03.jpg" style="width:100px;">
    <br><br>
    <p><strong>KAFE NILU</strong></p>
    <p>Jl. Sorowajan Baru, Jomblangan, Banguntapan, Bantul, Yogyakarta<br>
    Telp: 0857 4318 5987 | IG: @nilu_sorowajan</p>
    <br>
    <p><strong>STRUK PEMBAYARAN</strong></p>
  </div>

  <p>
    No Nota: <strong>{{ $receipt->invoice_number }}</strong><br>
    Tanggal: <strong>{{ \Carbon\Carbon::parse($receipt->paid_at)->format('d/m/Y H:i:s') }}</strong><br>
    Meja: <strong>{{ $receipt->table_number }}</strong><br>
    Kasir: <strong>{{ $receipt->cashier_name }}</strong><br>
  </p>

  <div class="line"></div>

  <table>
    @foreach ($orders as $order)
      @foreach ($order->items as $item)
        <tr>
          <td>{{ $item->quantity }} x {{ $item->menu->name }}</td>
          <td class="amount">Rp. {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    @endforeach

    <tr>
      <td>Subtotal</td>
      <td class="amount">Rp. {{ number_format($receipt->total_price, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td>PPN (10%)</td>
      <td class="amount">Rp. {{ number_format($receipt->tax_amount, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td>Biaya Layanan (5%)</td>
      <td class="amount">Rp. {{ number_format($receipt->service_charge, 0, ',', '.') }}</td>
    </tr>
    <tr class="total">
      <td>Total</td>
      <td class="amount">Rp. {{ number_format($receipt->grand_total, 0, ',', '.') }}</td>
    </tr>
  </table>

  <div class="line"></div>

  <div>
    Pembayaran: <strong>{{ $receipt->payment_type == 'qris' ? 'QRIS' : 'Tunai' }}</strong><br>
    @if($receipt->payment_type == 'cash')
      Uang Diterima: <strong>Rp. {{ number_format($receipt->cash_amount, 0, ',', '.') }}</strong><br>
      Kembalian: <strong>Rp. {{ number_format($receipt->change, 0, ',', '.') }}</strong><br>
    @endif
  </div>

  <div class="line"></div>

<div class="footer">
  <p class="small-text">Kritik Saran Pujian Whatsapp 0857 4318 5987 <br>DM IG @nilu_sorowajan</p>
  <p class="small-text">Terbayar: {{ \Carbon\Carbon::parse($receipt->paid_at)->format('d/m/Y H:i:s') }}</p>
</div>

  <script>
    window.onload = function() {
        window.print();
    };
  </script>

</body>
</html>
