<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return Permission::query()
            ->orderBy('name')
            ->get()
            ->map(function ($permission) {

                [$module, $action] = explode('.', $permission->name);

                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'module' => ucfirst($module),
                    'action' => ucfirst(str_replace('_', ' ', $action)),
                ];
            });
    }
}
