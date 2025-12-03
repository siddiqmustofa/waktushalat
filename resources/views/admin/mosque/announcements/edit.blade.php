<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Edit Pengumuman</h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Formulir</div>
                <div class="text-sm text-slate-500">Ubah judul, isi, periode, dan status.</div>
            </div>
            <form method="POST" action="{{ route('announcements.update', $item) }}" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('PUT')
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Judul</label>
                    <input type="text" name="title" class="mt-2 w-full" value="{{ old('title', $item->title) }}" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Isi</label>
                    <textarea name="body" class="mt-2 w-full" rows="3">{{ old('body', $item->body) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Mulai</label>
                    <input type="datetime-local" name="starts_at" class="mt-2 w-full" value="{{ old('starts_at', optional($item->starts_at)?->format('Y-m-d\TH:i')) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium">Selesai</label>
                    <input type="datetime-local" name="ends_at" class="mt-2 w-full" value="{{ old('ends_at', optional($item->ends_at)?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active))>
                    <span class="text-sm">Aktif</span>
                </div>
                <div class="md:col-span-2">
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
