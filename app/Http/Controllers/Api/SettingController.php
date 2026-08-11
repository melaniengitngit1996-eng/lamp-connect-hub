<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settings
    ) {}

    /**
     * Return all settings.
     */
    public function index()
    {
        return response()->json(
            $this->settings->all()
        );
    }

    /**
     * Save all settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string'],
            'value' => ['nullable'],
        ]);

        $this->settings->set(
            $request->key,
            $request->value
        );

        return response()->json([
            'message' => 'Setting updated.'
        ]);
    }

    protected function rules(): array
    {
        $rules = [];

        foreach (config('settings') as $group => $items) {
            foreach ($items as $key => $config) {
                $rules["{$group}.{$key}"] = $config['rules'] ?? [];
            }
        }

        return $rules;
    }
}
