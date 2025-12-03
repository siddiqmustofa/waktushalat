<?php

namespace App\Http\Controllers\Admin\Mosque;

use App\Http\Controllers\Controller;
use App\Models\RunningText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RunningTextController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mosque = Auth::user()->mosque_id;
        $items = RunningText::where('mosque_id', $mosque)->latest()->paginate(50);

        return view('admin.mosque.running_texts.index', compact('items'));
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
            'content' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        RunningText::create(array_merge(['mosque_id' => $mosque], $data));

        return back();
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RunningText $runningText)
    {
        $this->authorizeMosque($runningText->mosque_id);
        $runningText->delete();

        return back();
    }

    protected function authorizeMosque(int $mosqueId)
    {
        abort_unless(Auth::user()->mosque_id === $mosqueId, 403);
    }
}
