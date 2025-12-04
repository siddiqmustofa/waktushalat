<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Petugas Jum'at</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Kelola petugas Jumat</span>
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
                <h6>Tambah petugas</h6>
                <p class="text-sm">Isi petugas untuk tanggal Jum'at tertentu.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('friday-officers.store') }}">
                    @csrf
                    <div class="row g-3 g-md-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Tanggal Jum'at</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Khatib</label>
                            <input type="text" name="khatib" class="form-control" placeholder="Nama khatib">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Imam</label>
                            <input type="text" name="imam" class="form-control" placeholder="Nama imam">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Muadzin</label>
                            <input type="text" name="muadzin" class="form-control" placeholder="Nama muadzin">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Bilal</label>
                            <input type="text" name="bilal" class="form-control" placeholder="Nama bilal">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan tambahan"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 w-sm-auto">Simpan petugas</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3 mt-md-4">
            <div class="card-header pb-0">
                <h6>Daftar petugas</h6>
            </div>
            <div class="card-body">
                <div class="d-none d-sm-block">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Khatib</th>
                                    <th>Imam</th>
                                    <th>Muadzin</th>
                                    <th>Bilal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i)
                                    <tr>
                                        <td>{{ optional($i->date)?->format('d M Y') }}</td>
                                        <td>{{ $i->khatib }}</td>
                                        <td>{{ $i->imam }}</td>
                                        <td>{{ $i->muadzin }}</td>
                                        <td>{{ $i->bilal }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('friday-officers.edit', $i) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form method="POST" action="{{ route('friday-officers.destroy', $i) }}" class="d-inline">
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
                            <div class="text-break"><strong>{{ optional($i->date)?->format('d M Y') }}</strong></div>
                            <div class="text-break">Khatib: {{ $i->khatib }}</div>
                            <div class="text-break">Imam: {{ $i->imam }}</div>
                            <div class="text-break">Muadzin: {{ $i->muadzin }}</div>
                            <div class="text-break">Bilal: {{ $i->bilal }}</div>
                            <div class="mt-2 d-grid gap-2">
                                <a href="{{ route('friday-officers.edit', $i) }}" class="btn btn-warning">Edit</a>
                                <form method="POST" action="{{ route('friday-officers.destroy', $i) }}">
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
