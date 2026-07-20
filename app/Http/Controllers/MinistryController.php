<?php

namespace App\Http\Controllers;

use App\Models\LocalChurch;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MinistryController extends Controller
{
    public function all(Request $request)
    {
        return Ministry::query()
            ->with('localChurch')
            ->when($request->local_church_id, function ($query, $churchId) {
                $query->where('local_church_id', $churchId);
            })
            ->latest()
            ->get();
    }

    public function index(LocalChurch $localChurch)
    {
        return $localChurch->ministries()
            ->orderBy('name')
            ->get();
    }

    public function destroy(Ministry $ministry)
    {
        abort_unless(
            auth()->user()->can('lookups.delete'),
            403
        );

        $ministry->delete();

        return response()->json([
            'message' => 'Ministry deleted successfully.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'local_church_id' => ['required', 'exists:local_churches,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ministries')
                    ->where(fn($query) => $query->where(
                        'local_church_id',
                        $request->local_church_id
                    )),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $ministry = Ministry::create($validated);

        return response()->json($ministry, 201);
    }

    public function update(Request $request, Ministry $ministry)
    {
        $validated = $request->validate([
            'local_church_id' => ['required', 'exists:local_churches,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ministries')
                    ->ignore($ministry)
                    ->where(fn($query) => $query->where(
                        'local_church_id',
                        $request->local_church_id
                    )),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $ministry->update($validated);

        return response()->json($ministry);
    }
}
