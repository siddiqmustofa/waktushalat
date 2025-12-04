<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Running Text</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Kelola teks berjalan</span>
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
                <h6>Tambah teks berjalan</h6>
                <p class="text-sm">Isi teks dan status aktif.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('running-texts.store') }}">
                    @csrf
                    <div class="row g-3 g-md-4">
                        <div class="col-12">
                            <label class="form-label">Teks</label>
                            <input type="text" name="content" class="form-control" placeholder="Contoh: Informasi kegiatan masjid" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 w-sm-auto">Simpan teks</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3 mt-md-4">
            <div class="card-header pb-0">
                <h6>Daftar teks</h6>
            </div>
            <div class="card-body">
                <div class="d-none d-sm-block">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Teks</th>
                                    <th>Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i)
                                    <tr>
                                        <td class="text-truncate" style="max-width: 60ch">{{ $i->content }}</td>
                                        <td>{{ $i->is_active ? 'Ya' : 'Tidak' }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('running-texts.destroy', $i) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-block d-sm-none">
                    @foreach($items as $i)
                        <div class="border rounded p-3 mb-2 w-100">
                            <div class="d-flex flex-column gap-2">
                                <div class="text-break">{{ $i->content }}</div>
                                <span class="badge {{ $i->is_active ? 'bg-success' : 'bg-secondary' }} align-self-start">{{ $i->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </div>
                            <div class="mt-2">
                                <form method="POST" action="{{ route('running-texts.destroy', $i) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger w-100">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
