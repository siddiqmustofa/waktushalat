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
                    <input type="text" name="name" class="mt-2 w-full" required />
                </div>
                <div>
                    <label class="block text-sm font-medium">Slug</label>
                    <input type="text" name="slug" class="mt-2 w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Alamat</label>
                    <textarea name="address" class="mt-2 w-full" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Timezone (contoh: Asia/Jakarta)</label>
                    <input type="text" name="timezone" class="mt-2 w-full" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Email Admin</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full" {{ request()->routeIs('public.mosques.create') ? 'required' : '' }} />
                        @error('email')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Password</label>
                        <input type="password" name="password" class="mt-2 w-full" {{ request()->routeIs('public.mosques.create') ? 'required' : '' }} />
                        @error('password')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="mt-2 w-full" {{ request()->routeIs('public.mosques.create') ? 'required' : '' }} />
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
