<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $usersByRole = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');
        
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('superadmin.dashboard', [
            'user' => auth()->user(),
            'totalUsers' => $totalUsers,
            'usersByRole' => $usersByRole,
            'recentUsers' => $recentUsers,
        ]);
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return view('superadmin.users', [
            'users' => $users,
        ]);
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('superadmin.edit-user', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,admin,superadmin',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('superadmin.users')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Não pode deletar a si mesmo
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Você não pode deletar seu próprio usuário!');
        }

        $user->delete();

        return redirect()->route('superadmin.users')
            ->with('success', 'Usuário deletado com sucesso!');
    }

    public function settings()
    {
        return view('superadmin.settings');
    }
}
