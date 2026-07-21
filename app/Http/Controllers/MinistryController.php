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
        return Ministry::with('localChurch')
            ->when(
                $request->boolean('national'),
                fn($query) => $query->whereNull('local_church_id')
            )
            ->when(
                $request->filled('local_church_id'),
                fn($query) => $query->where(
                    'local_church_id',
                    $request->local_church_id
                )
            )
            ->orderBy('name')
            ->get();
    }

    public function index(LocalChurch $localChurch)
    {
        return Ministry::with('localChurch')
            ->where(function ($query) use ($localChurch) {
                $query->where('local_church_id', $localChurch->id)
                    ->orWhereNull('local_church_id');
            })
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
            'local_church_id' => ['nullable', 'exists:local_churches,id'],
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
            'local_church_id' => ['nullable', 'exists:local_churches,id'],
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
