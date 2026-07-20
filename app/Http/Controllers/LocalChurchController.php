<?php

namespace App\Http\Controllers;

use App\Models\LocalChurch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocalChurchController extends Controller
{
    public function index()
    {
        return LocalChurch::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code'
            ]);
    }

    public function destroy(LocalChurch $localChurch)
    {
        abort_unless(
            auth()->user()->can('lookups.delete'),
            403
        );

        $localChurch->delete();

        return response()->json([
            'message' => 'Local Church deleted successfully.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:local_churches,code'],
        ]);

        return LocalChurch::create($validated);
    }

    public function update(Request $request, LocalChurch $localChurch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('local_churches', 'code')->ignore($localChurch),
            ],
        ]);

        $localChurch->update($validated);

        return $localChurch;
    }
}
