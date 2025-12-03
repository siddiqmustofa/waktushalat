<?php

namespace App\Http\Controllers\Admin\Mosque;

use App\Http\Controllers\Controller;
use App\Models\FridayOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FridayOfficerController extends Controller
{
    public function index()
    {
        $mosque = Auth::user()->mosque_id;
        $items = FridayOfficer::where('mosque_id', $mosque)->orderBy('date', 'desc')->paginate(20);
        return view('admin.mosque.friday_officers.index', compact('items'));
    }

    public function store(Request $request)
    {
        $mosque = Auth::user()->mosque_id;
        $data = $request->validate([
            'date' => 'required|date',
            'khatib' => 'nullable|string|max:255',
            'imam' => 'nullable|string|max:255',
            'muadzin' => 'nullable|string|max:255',
            'bilal' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        FridayOfficer::create(array_merge(['mosque_id' => $mosque], $data));
        return back();
    }

    public function destroy(FridayOfficer $fridayOfficer)
    {
        $this->authorizeMosque($fridayOfficer->mosque_id);
        $fridayOfficer->delete();
        return back();
    }

    public function edit(string $id)
    {
        $mosque = Auth::user()->mosque_id;
        $item = FridayOfficer::where('mosque_id', $mosque)->findOrFail($id);
        return view('admin.mosque.friday_officers.edit', compact('item'));
    }

    public function update(Request $request, string $id)
    {
        $mosque = Auth::user()->mosque_id;
        $item = FridayOfficer::where('mosque_id', $mosque)->findOrFail($id);
        $data = $request->validate([
            'date' => 'required|date',
            'khatib' => 'nullable|string|max:255',
            'imam' => 'nullable|string|max:255',
            'muadzin' => 'nullable|string|max:255',
            'bilal' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $item->update($data);
        return redirect()->route('friday-officers.index');
    }

    protected function authorizeMosque(int $mosqueId)
    {
        abort_unless(Auth::user()->mosque_id === $mosqueId, 403);
    }
}
