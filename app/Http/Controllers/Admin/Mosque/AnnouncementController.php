<?php

namespace App\Http\Controllers\Admin\Mosque;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mosque = Auth::user()->mosque_id;
        $items = Announcement::where('mosque_id', $mosque)->latest()->paginate(20);

        return view('admin.mosque.announcements.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mosque = Auth::user()->mosque_id;
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
        ]);
        Announcement::create(array_merge(['mosque_id' => $mosque], $data));

        return back()->with('status', 'Pengumuman ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mosque = Auth::user()->mosque_id;
        $item = Announcement::where('mosque_id', $mosque)->findOrFail($id);
        return view('admin.mosque.announcements.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mosque = Auth::user()->mosque_id;
        $item = Announcement::where('mosque_id', $mosque)->findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
        ]);
        $item->update($data);
        return redirect()->route('announcements.index')->with('status', 'Pengumuman diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorizeMosque($announcement->mosque_id);
        $announcement->delete();

        return back()->with('status', 'Pengumuman dihapus.');
    }

    protected function authorizeMosque(int $mosqueId)
    {
        abort_unless(Auth::user()->mosque_id === $mosqueId, 403);
    }
}
