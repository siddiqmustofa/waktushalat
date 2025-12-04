@if(request()->routeIs('public.mosques.create'))
<x-guest-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Registrasi Masjid</h6>
                        <p class="text-sm">Isi data masjid dan akun admin.</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ $action ?? route('mosques.store') }}" class="space-y-4">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="name" id="mosque-name" class="form-control" required autocomplete="organization">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Slug</label>
                                        <input type="text" name="slug" id="mosque-slug" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="address" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Timezone (contoh: Asia/Jakarta)</label>
                                        <input type="text" name="timezone" class="form-control" placeholder="Asia/Jakarta" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Aktif</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                            <label class="form-check-label" for="is_active">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Email Admin</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autocomplete="email" inputmode="email">
                                        @error('email')
                                            <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required autocomplete="new-password">
                                        @error('password')
                                            <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                        <script>
                        (function(){
                          var nameEl = document.querySelector('#mosque-name');
                          var slugEl = document.querySelector('#mosque-slug');
                          if (!nameEl || !slugEl) return;
                          var manual = false;
                          slugEl.addEventListener('input', function(){ manual = true; });
                          function slugify(s){
                            return String(s||'').toLowerCase()
                              .normalize('NFD').replace(/[\u0300-\u036f]/g,'')
                              .replace(/[^a-z0-9]+/g,'-')
                              .replace(/^-+|-+$/g,'')
                              .replace(/-+/g,'-');
                          }
                          function update(){ if (manual && slugEl.value.trim() !== '') return; slugEl.value = slugify(nameEl.value); }
                          nameEl.addEventListener('input', update);
                          nameEl.addEventListener('blur', update);
                        })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
@else
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Tambah Masjid</h2>
        </div>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Formulir</div>
                <div class="text-sm text-slate-500">Data dasar untuk registrasi masjid.</div>
            </div>
            <form method="POST" action="{{ $action ?? route('mosques.store') }}" class="p-6 space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="name" class="mt-2 w-full" required autocomplete="organization" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Slug</label>
                    <input type="text" name="slug" class="mt-2 w-full" autocomplete="off" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Alamat</label>
                    <textarea name="address" class="mt-2 w-full" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Timezone (contoh: Asia/Jakarta)</label>
                    <input type="text" name="timezone" class="mt-2 w-full" placeholder="Asia/Jakarta" autocomplete="off" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Email Admin</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full" {{ request()->routeIs('public.mosques.create') ? 'required' : '' }} autocomplete="email" inputmode="email" />
                        @error('email')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Password</label>
                        <input type="password" name="password" class="mt-2 w-full" {{ request()->routeIs('public.mosques.create') ? 'required' : '' }} autocomplete="new-password" />
                        @error('password')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="mt-2 w-full" {{ request()->routeIs('public.mosques.create') ? 'required' : '' }} autocomplete="new-password" />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input id="is_active" type="checkbox" name="is_active" value="1" checked />
                    <label for="is_active" class="text-sm">Aktif</label>
                </div>
                <div>
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
@endif
