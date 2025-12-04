<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Edit Pengumuman</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Ubah judul, isi, periode, dan status</span>
        </div>
    </x-slot>

    <div class="container-fluid py-3 py-md-4">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Formulir</h6>
                <p class="text-sm">Ubah judul, isi, periode, dan status.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('announcements.update', $item) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 g-md-4">
                        <div class="col-12">
                            <label class="form-label">Judul</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Isi</label>
                            <textarea name="body" class="form-control" rows="6">{{ old('body', $item->body) }}</textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Mulai</label>
                            <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', optional($item->starts_at)?->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Selesai</label>
                            <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', optional($item->ends_at)?->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $item->is_active))>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 w-sm-auto">Simpan perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
