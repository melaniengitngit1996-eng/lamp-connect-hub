<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\LocalChurch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClusterGroupController extends Controller
{
    public function all(Request $request)
    {
        return Cluster::with('localChurch')
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
        return $localChurch->clusters()
            ->orderBy('name')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'local_church_id' => ['required', 'exists:local_churches,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clusters')
                    ->where(fn($query) => $query->where(
                        'local_church_id',
                        $request->local_church_id
                    )),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $clusterGroup = Cluster::create($validated);

        return response()->json($clusterGroup, 201);
    }

    public function update(Request $request, Cluster $cluster)
    {
        $validated = $request->validate([
            'local_church_id' => ['required', 'exists:local_churches,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clusters')
                    ->ignore($cluster->id)
                    ->where(fn($query) => $query->where(
                        'local_church_id',
                        $request->local_church_id
                    )),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $cluster->update($validated);

        return response()->json($cluster);
    }

    public function destroy(Cluster $cluster)
    {
        abort_unless(
            auth()->user()->can('lookups.delete'),
            403
        );

        $cluster->delete();

        return response()->json([
            'message' => 'Cluster group deleted successfully.',
        ]);
    }
}
