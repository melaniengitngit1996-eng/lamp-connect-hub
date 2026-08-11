<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    /**
     * Get a setting.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = Setting::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    /**
     * Save a setting.
     */
    public function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Save many settings.
     */
    public function setMany(array $settings): void
    {
        foreach ($settings as $group => $items) {
            foreach ($items as $key => $value) {
                $this->set("$group.$key", $value);
            }
        }
    }

    /**
     * Get all settings grouped by section.
     */
    public function all(): array
    {
        $settings = [];

        foreach (config('settings') as $group => $items) {
            foreach ($items as $key => $config) {
                $settings[$group][$key] = $this->get(
                    "$group.$key",
                    $config['default'] ?? null
                );
            }
        }

        return $settings;
    }
}
