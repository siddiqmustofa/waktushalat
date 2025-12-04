<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Edit Masjid</h2>
        </div>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Formulir</div>
                <div class="text-sm text-slate-500">Ubah data masjid dan email/password admin.</div>
            </div>
            <form method="POST" action="{{ route('mosques.update', $mosque) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $mosque->name) }}" class="mt-2 w-full" required />
                </div>
                <div>
                    <label class="block text-sm font-medium">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $mosque->slug) }}" class="mt-2 w-full" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Alamat</label>
                    <textarea name="address" class="mt-2 w-full" rows="3">{{ old('address', $mosque->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Timezone</label>
                    <input type="text" name="timezone" value="{{ old('timezone', $mosque->timezone) }}" class="mt-2 w-full" />
                </div>
                <div class="flex items-center gap-2">
                    <input id="is_active" type="checkbox" name="is_active" value="1" @checked($mosque->is_active) />
                    <label for="is_active" class="text-sm">Aktif</label>
                </div>
                <hr />
                @php($admin = \App\Models\User::where('mosque_id', $mosque->id)->where('role','mosque_admin')->first())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium">Email Admin</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email', optional($admin)->email) }}" class="mt-2 w-full" />
                        <div class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah email admin.</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Reset Password Admin</label>
                        <input type="password" name="admin_password" class="mt-2 w-full" placeholder="Password baru" />
                        <input type="password" name="admin_password_confirmation" class="mt-2 w-full" placeholder="Konfirmasi password" />
                        <div class="text-xs text-slate-500 mt-1">Isi untuk mengganti password admin. Biarkan kosong jika tidak diubah.</div>
                    </div>
                </div>
                <div>
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
