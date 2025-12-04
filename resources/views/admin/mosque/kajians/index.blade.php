<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Jadwal Kajian</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Kelola jadwal kajian</span>
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
                <h6>Tambah jadwal kajian</h6>
                <p class="text-sm">Judul, penceramah, waktu, lokasi, status aktif.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('kajians.store') }}">
                    @csrf
                    <div class="row g-3 g-md-4">
                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Tafsir Al-Qur'an" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Penceramah</label>
                            <input type="text" name="speaker" class="form-control" placeholder="Nama penceramah">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="location" class="form-control" placeholder="Ruang utama / aula">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Mulai</label>
                            <input type="datetime-local" name="starts_at" class="form-control" required>
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
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 w-sm-auto">Simpan jadwal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3 mt-md-4">
            <div class="card-header pb-0">
                <h6>Daftar kajian</h6>
            </div>
            <div class="card-body">
                <div class="d-none d-sm-block">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Penceramah</th>
                                    <th>Waktu</th>
                                    <th>Lokasi</th>
                                    <th>Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i)
                                    <tr>
                                        <td class="text-truncate" style="max-width: 40ch">{{ $i->title }}</td>
                                        <td>{{ $i->speaker }}</td>
                                        <td>{{ optional($i->starts_at)?->format('d M Y H:i') }} @if($i->ends_at) - {{ $i->ends_at->format('d M Y H:i') }} @endif</td>
                                        <td>{{ $i->location }}</td>
                                        <td>{{ $i->is_active ? 'Ya' : 'Tidak' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('kajians.edit', $i) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form method="POST" action="{{ route('kajians.destroy', $i) }}" class="d-inline">
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
                </div>
                <div class="d-block d-sm-none">
                    @foreach($items as $i)
                        <div class="border rounded p-3 mb-2 w-100">
                            <div class="text-break"><strong>{{ $i->title }}</strong></div>
                            <div class="text-break">Penceramah: {{ $i->speaker }}</div>
                            <div class="text-break">Waktu: {{ optional($i->starts_at)?->format('d M Y H:i') }}@if($i->ends_at) - {{ $i->ends_at->format('d M Y H:i') }}@endif</div>
                            <div class="text-break">Lokasi: {{ $i->location }}</div>
                            <span class="badge {{ $i->is_active ? 'bg-success' : 'bg-secondary' }} align-self-start mt-2">{{ $i->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            <div class="mt-2 d-grid gap-2">
                                <a href="{{ route('kajians.edit', $i) }}" class="btn btn-warning">Edit</a>
                                <form method="POST" action="{{ route('kajians.destroy', $i) }}">
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
