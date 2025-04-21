@extends('admin.layout')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endpush

@section('content')
  <h2>Pengaturan Menu</h2>

  @if(session('success'))
    <div id="snackbar" class="position-fixed bottom-0 end-0 m-4 bg-success text-white p-3 rounded shadow">
      {{ session('success') }}
    </div>
  @endif

  <div class="container-btn mb-3">
    <a href="/admin/menus/create">
      <button type="button" class="btn btn-outline-dark">Tambahkan Menu</button>
    </a>
  </div>

  <table class="table table-striped mt-4">
    <thead>
      <tr>
        <th>#</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($menus as $index => $menu)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $menu->name }}</td>
          <td>Rp. {{ number_format($menu->price, 0, ',', '.') }}</td>
          <td>{{ ucfirst($menu->category) }}</td>
          <td>
            <button type="button" class="btn btn-sm {{ $menu->is_available ? 'btn-success' : 'btn-secondary' }}"
              data-bs-toggle="modal" data-bs-target="#statusModal{{ $menu->id }}">
              {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
            </button>
          </td>
          <td>
            <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display:inline-block;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus menu ini?')">Delete</button>
            </form>
          </td>
        </tr>

        <!-- Modal -->
        <div class="modal fade" id="statusModal{{ $menu->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $menu->id }}" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <form action="/admin/menus/update/{{ $menu->id }}" method="POST">
              @csrf
              <input type="hidden" name="status_only" value="1">

              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="statusModalLabel{{ $menu->id }}">Ubah Ketersediaan Menu</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                  <p>Menu: <strong>{{ $menu->name }}</strong></p>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_available" id="available{{ $menu->id }}" value="1" {{ $menu->is_available ? 'checked' : '' }} required>
                    <label class="form-check-label" for="available{{ $menu->id }}">Tersedia</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="is_available" id="not_available{{ $menu->id }}" value="0" {{ !$menu->is_available ? 'checked' : '' }} required>
                    <label class="form-check-label" for="not_available{{ $menu->id }}">Habis</label>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      @endforeach
    </tbody>
  </table>

  <script>
    window.onload = function () {
      const snackbar = document.getElementById('snackbar');
      if (snackbar) {
        setTimeout(() => {
          snackbar.classList.add('fade');
          setTimeout(() => snackbar.remove(), 500);
        }, 4000);
      }
    };
  </script>

  <style>
    #snackbar.fade {
      opacity: 0;
      transition: opacity 0.5s ease-out;
    }
  </style>
@endsection
