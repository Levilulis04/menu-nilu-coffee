# Sistem Pemesanan Menu Digital Kafe Nilu

Proyek ini adalah sistem pemesanan menu makanan berbasis web menggunakan QR Code.  
Dibuat untuk mempermudah pelanggan dalam memesan makanan secara digital tanpa harus antri ke kasir.

## Fitur

-   Scan QR Code untuk melihat daftar menu
-   Tambah menu ke keranjang
-   Lakukan pemesanan langsung dari ponsel
-   Update status pesanan secara real-time oleh dapur/admin
-   Laporan harian pesanan untuk admin/dapur
-   Cetak struk pemesanan

## Teknologi yang Digunakan

-   Laravel 11
-   Livewire
-   MySQL
-   JavaScript

## Cara Instalasi

1. Clone repository ini:
    ```bash
    git clone https://github.com/username/nama-repo.git
    ```
2. Masuk ke folder proyek:
    ```bash
    cd nama-repo
    ```
3. Install dependency PHP:
    ```bash
    composer install
    ```
4. Copy file environment:
    ```bash
    cp .env.example .env
    ```
5. Konfigurasikan file `.env` untuk database dan setting lainnya.
6. Generate application key:
    ```bash
    php artisan key:generate
    ```
7. Jalankan migrasi database:
    ```bash
    php artisan migrate
    ```
8. Jalankan server lokal:
    ```bash
    php artisan serve
    ```

## Cara Menggunakan

-   Pelanggan memindai QR Code yang mengarahkan ke halaman menu.
-   Pelanggan memilih makanan dan memasukkannya ke keranjang.
-   Pelanggan mengirim pesanan.
-   Admin/dapur melihat daftar pesanan dan memperbarui status pesanan.
-   Admin dapat mencetak laporan harian pesanan.

## Contoh QR Code

Scan QR Code berikut untuk melihat tampilan menu:

![QR Code](https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://your-website-link.com)

_(Ganti `https://your-website-link.com` dengan link ke halaman menu kamu.)_

## Kontributor

-   Levi Lulis Narulista

## Lisensi

Proyek ini bebas digunakan untuk tujuan pembelajaran.
