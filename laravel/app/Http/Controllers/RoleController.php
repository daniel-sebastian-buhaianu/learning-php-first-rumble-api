<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Role::class))
        {
            abort(403, 'Unauthorized');
        }

        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        return Role::create([
            'name' => $request->input('name')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        if ($request->user()->cannot('view', $role))
        {
            abort(403, 'Unauthorized');
        }

        return $role;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $role = Role::findOrFail($id);
        
        $role->update([
            'name' => $request->input('name')
        ]);

        return $role; 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        if ($request->user()->cannot('delete', $role))
        {
            abort(403, 'Unauthorized');
        }

        return Role::destroy($id);
    }
}
