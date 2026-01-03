<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('start_date', 'desc')->paginate(25);
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($holidays);
        }
        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        if (request()->ajax()) {
            return view('holidays._form');
        }
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();

        $holiday = Holiday::create($data);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'holiday' => $holiday]);
        }
        return redirect()->route('holidays.index')->with('success', 'Holiday created.');
    }

    public function edit(Holiday $holiday)
    {
        if (request()->ajax()) {
            return view('holidays._form', compact('holiday'));
        }
        return view('holidays.create', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $holiday->update($data);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'holiday' => $holiday]);
        }
        return redirect()->route('holidays.index')->with('success', 'Holiday updated.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('holidays.index')->with('success', 'Holiday removed.');
    }
}
