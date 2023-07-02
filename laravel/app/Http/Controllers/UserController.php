<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', User::class))
        {
            abort(403, 'Unauthorized');
        }

        return User::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->cannot('view', $user))
        {
            abort(403, 'Unauthorized');
        }

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);

        $name = $request->input('name', $user->name);
        $email = $request->input('email', $user->email);
        $role_id = $request->input('role_id', $user->role_id);

        $user->update([
            'name' => $name,
            'email' => $email,
        ]);

        if ($request->user()->can('updateUserRole', User::class))
        {
            $user->update([
                'role_id' => intval($role_id)
            ]);
        }

        return $user;     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->cannot('delete', $user))
        {
            abort(403, 'Unauthorized');
        }

        return User::destroy($id);
    }
}
