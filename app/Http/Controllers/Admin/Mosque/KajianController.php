<?php

namespace App\Http\Controllers\Admin\Mosque;

use App\Http\Controllers\Controller;
use App\Models\Kajian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KajianController extends Controller
{
    public function index()
    {
        $mosque = Auth::user()->mosque_id;
        $items = Kajian::where('mosque_id', $mosque)->latest()->paginate(20);
        return view('admin.mosque.kajians.index', compact('items'));
    }

    public function store(Request $request)
    {
        $mosque = Auth::user()->mosque_id;
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        Kajian::create(array_merge(['mosque_id' => $mosque], $data));
        return back()->with('status', 'Kajian ditambahkan.');
    }

    public function destroy(Kajian $kajian)
    {
        $this->authorizeMosque($kajian->mosque_id);
        $kajian->delete();
        return back()->with('status', 'Kajian dihapus.');
    }

    public function edit(string $id)
    {
        $mosque = Auth::user()->mosque_id;
        $item = Kajian::where('mosque_id', $mosque)->findOrFail($id);
        return view('admin.mosque.kajians.edit', compact('item'));
    }

    public function update(Request $request, string $id)
    {
        $mosque = Auth::user()->mosque_id;
        $item = Kajian::where('mosque_id', $mosque)->findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        $item->update($data);
        return redirect()->route('kajians.index');
    }

    protected function authorizeMosque(int $mosqueId)
    {
        abort_unless(Auth::user()->mosque_id === $mosqueId, 403);
    }
}
