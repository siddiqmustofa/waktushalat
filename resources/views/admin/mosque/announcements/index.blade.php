<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Pengumuman</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Kelola pengumuman masjid</span>
        </div>
    </x-slot>

    <div class="container-fluid py-3 py-md-4">
        @if(session('status'))
            <div class="mx-6">
                <div class="rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3">{{ session('status') }}</div>
            </div>
        @endif
        <div class="card">
            <div class="card-header pb-0">
                <h6>Tambah pengumuman</h6>
                <p class="text-sm">Judul, isi, periode aktif, dan status.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('announcements.store') }}">
                    @csrf
                    <div class="row g-3 g-md-4">
                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Taushiyah hari ini" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Isi</label>
                            <textarea name="body" class="form-control" rows="6" placeholder="Tulis isi pengumuman"></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Mulai</label>
                            <input type="datetime-local" name="starts_at" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Selesai</label>
                            <input type="datetime-local" name="ends_at" class="form-control">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 w-sm-auto">Simpan pengumuman</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3 mt-md-4">
            <div class="card-header pb-0">
                <h6>Daftar pengumuman</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th class="d-none d-sm-table-cell">Aktif</th>
                                <th class="d-none d-md-table-cell text-nowrap">Periode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i)
                                <tr>
                                    <td class="text-truncate" style="max-width: 40ch">{{ $i->title }}</td>
                                    <td class="d-none d-sm-table-cell">{{ $i->is_active ? 'Ya' : 'Tidak' }}</td>
                                    <td class="d-none d-md-table-cell text-nowrap">{{ optional($i->starts_at)?->format('d M Y H:i') }} — {{ optional($i->ends_at)?->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <a href="{{ route('announcements.edit', $i) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form method="POST" action="{{ route('announcements.destroy', $i) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
