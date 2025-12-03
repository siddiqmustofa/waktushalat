<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Edit Petugas Jum'at</h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b">
                <div class="text-lg font-semibold">Formulir</div>
                <div class="text-sm text-slate-500">Ubah data petugas untuk tanggal Jum'at.</div>
            </div>
            <form method="POST" action="{{ route('friday-officers.update', $item) }}" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Tanggal Jum'at</label>
                    <input type="date" name="date" class="mt-2 w-full" value="{{ old('date', optional($item->date)?->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Khatib</label>
                    <input type="text" name="khatib" class="mt-2 w-full" value="{{ old('khatib', $item->khatib) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium">Imam</label>
                    <input type="text" name="imam" class="mt-2 w-full" value="{{ old('imam', $item->imam) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium">Muadzin</label>
                    <input type="text" name="muadzin" class="mt-2 w-full" value="{{ old('muadzin', $item->muadzin) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium">Bilal</label>
                    <input type="text" name="bilal" class="mt-2 w-full" value="{{ old('bilal', $item->bilal) }}">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Catatan</label>
                    <textarea name="notes" class="mt-2 w-full" rows="3">{{ old('notes', $item->notes) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
