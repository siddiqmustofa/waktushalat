<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Petugas Jum'at</h2>
        </div>
    </x-slot>

    <div class="p-6 space-y-8 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Tambah petugas</div>
                <div class="text-sm text-slate-500">Isi petugas untuk tanggal Jum'at tertentu.</div>
            </div>
            <form method="POST" action="{{ route('friday-officers.store') }}" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Tanggal Jum'at</label>
                    <input type="date" name="date" class="mt-2 w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Khatib</label>
                    <input type="text" name="khatib" class="mt-2 w-full" placeholder="Nama khatib">
                </div>
                <div>
                    <label class="block text-sm font-medium">Imam</label>
                    <input type="text" name="imam" class="mt-2 w-full" placeholder="Nama imam">
                </div>
                <div>
                    <label class="block text-sm font-medium">Muadzin</label>
                    <input type="text" name="muadzin" class="mt-2 w-full" placeholder="Nama muadzin">
                </div>
                <div>
                    <label class="block text-sm font-medium">Bilal</label>
                    <input type="text" name="bilal" class="mt-2 w-full" placeholder="Nama bilal">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Catatan</label>
                    <textarea name="notes" class="mt-2 w-full" rows="3" placeholder="Catatan tambahan"></textarea>
                </div>
                <div class="md:col-span-2">
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Simpan petugas</button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Daftar petugas</div>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-slate-500">
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Khatib</th>
                            <th class="p-3 text-left">Imam</th>
                            <th class="p-3 text-left">Muadzin</th>
                            <th class="p-3 text-left">Bilal</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i)
                            <tr class="border-t">
                                <td class="p-3">{{ optional($i->date)?->format('d M Y') }}</td>
                                <td class="p-3">{{ $i->khatib }}</td>
                                <td class="p-3">{{ $i->imam }}</td>
                                <td class="p-3">{{ $i->muadzin }}</td>
                                <td class="p-3">{{ $i->bilal }}</td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('friday-officers.edit', $i) }}" class="px-3 py-1.5 bg-yellow-500 text-white rounded">Edit</a>
                                        <form method="POST" action="{{ route('friday-officers.destroy', $i) }}">
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
