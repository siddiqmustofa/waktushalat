<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Jadwal Kajian</h2>
        </div>
    </x-slot>

    <div class="p-6 space-y-8 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Tambah jadwal kajian</div>
                <div class="text-sm text-slate-500">Judul, penceramah, waktu, lokasi, status aktif.</div>
            </div>
            <form method="POST" action="{{ route('kajians.store') }}" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Judul</label>
                    <input type="text" name="title" class="mt-2 w-full" placeholder="Contoh: Tafsir Al-Qur'an" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Penceramah</label>
                    <input type="text" name="speaker" class="mt-2 w-full" placeholder="Nama penceramah">
                </div>
                <div>
                    <label class="block text-sm font-medium">Mulai</label>
                    <input type="datetime-local" name="starts_at" class="mt-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Selesai</label>
                    <input type="datetime-local" name="ends_at" class="mt-2 w-full">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Lokasi</label>
                    <input type="text" name="location" class="mt-2 w-full" placeholder="Ruang utama / aula">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Catatan</label>
                    <textarea name="notes" class="mt-2 w-full" rows="3" placeholder="Catatan tambahan"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked>
                    <span class="text-sm">Aktif</span>
                </div>
                <div class="md:col-span-2">
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Simpan jadwal</button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Daftar kajian</div>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-slate-500">
                            <th class="p-3 text-left">Judul</th>
                            <th class="p-3 text-left">Penceramah</th>
                            <th class="p-3 text-left">Waktu</th>
                            <th class="p-3 text-left">Lokasi</th>
                            <th class="p-3 text-left">Aktif</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i)
                            <tr class="border-t">
                                <td class="p-3 font-medium">{{ $i->title }}</td>
                                <td class="p-3">{{ $i->speaker }}</td>
                                <td class="p-3">{{ optional($i->starts_at)?->format('d M Y H:i') }} @if($i->ends_at) - {{ $i->ends_at->format('d M Y H:i') }} @endif</td>
                                <td class="p-3">{{ $i->location }}</td>
                                <td class="p-3">{{ $i->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('kajians.edit', $i) }}" class="px-3 py-1.5 bg-yellow-500 text-white rounded">Edit</a>
                                        <form method="POST" action="{{ route('kajians.destroy', $i) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1.5 bg-red-600 text-white rounded">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
