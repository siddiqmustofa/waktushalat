<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Profil Masjid</h2>
        </div>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Informasi dasar</div>
                <div class="text-sm text-slate-500">Nama, slug URL, alamat, dan timezone.</div>
            </div>
            <form method="POST" action="{{ route('mosque.profile') }}" class="p-6 space-y-6">
                @csrf
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
                    <input type="text" name="timezone" value="{{ old('timezone', $mosque->timezone) }}" class="mt-2 w-full" placeholder="Asia/Jakarta" />
                </div>
                <div>
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Simpan profil</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
