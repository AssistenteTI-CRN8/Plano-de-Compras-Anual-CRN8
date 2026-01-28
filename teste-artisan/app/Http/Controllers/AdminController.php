<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $usersByRole = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        return view('admin.dashboard', [
            'user' => auth()->user(),
            'totalUsers' => $totalUsers,
            'usersByRole' => $usersByRole,
        ]);
    }

    public function users()
    {
        $users = User::where('role', '!=', 'superadmin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        // Admin não pode editar superadmin
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Você não pode editar um Super Administrador.');
        }

        return view('admin.edit-user', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Admin não pode editar superadmin
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Você não pode editar um Super Administrador.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('admin.users')
            ->with('success', 'Usuário atualizado com sucesso!');
    }
}
