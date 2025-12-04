<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Daftar Masjid</h2>
            <a href="{{ route('mosques.create') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Tambah Masjid</a>
        </div>
    </x-slot>

    <div class="p-6 max-w-6xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-slate-500">
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Slug</th>
                            <th class="p-3 text-left">Email Admin</th>
                            <th class="p-3 text-left">Aktif</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mosques as $m)
                            <tr class="border-t">
                                <td class="p-3 font-medium">{{ $m->name }}</td>
                                <td class="p-3">{{ $m->slug }}</td>
                                <td class="p-3">{{ optional(\App\Models\User::where('mosque_id', $m->id)->where('role','mosque_admin')->first())->email ?? '-' }}</td>
                                <td class="p-3">{{ $m->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('mosques.edit', $m) }}" class="px-3 py-1.5 bg-yellow-500 text-white rounded">Edit</a>
                                        <form method="POST" action="{{ route('mosques.destroy', $m) }}">
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
                <div class="mt-4">{{ $mosques->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
