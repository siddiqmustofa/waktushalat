<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 text-truncate" style="max-width: 70%">Profil Masjid</h6>
            <span class="text-sm text-muted d-none d-sm-inline">Informasi dasar masjid</span>
        </div>
    </x-slot>

    <div class="container-fluid py-3 py-md-4">
        @if(session('status'))
            <div class="mx-3 mx-md-0">
                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
            </div>
        @endif

        <div class="card">
            <div class="card-header pb-0">
                <h6>Informasi dasar</h6>
                <p class="text-sm">Nama, slug URL, alamat, dan timezone.</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('mosque.profile') }}">
                    @csrf
                    <div class="row g-3 g-md-4">
                        <div class="col-12">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $mosque->name) }}" class="form-control" required />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $mosque->slug) }}" class="form-control" />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Timezone</label>
                            <input type="text" name="timezone" value="{{ old('timezone', $mosque->timezone) }}" class="form-control" placeholder="Asia/Jakarta" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $mosque->address) }}</textarea>
                        </div>
                        <div class="col-12 d-grid d-sm-inline">
                            <button class="btn btn-primary">Simpan profil</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
