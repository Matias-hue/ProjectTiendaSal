<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\LogActivity;

class UsuarioController extends Controller
{
    use LogActivity;

    public function index(Request $request)
    {
        $search = $request->input('search');
        $usuarios = $search
            ? User::where('name', 'LIKE', "%{$search}%")->paginate(10)
            : User::all();

        if ($request->ajax()) {
            return response()->json($usuarios);
        }

        return view('usuarios', compact('usuarios'));
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios-edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        try {
            $usuario = User::findOrFail($id);
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'role' => 'required|in:admin,user',
            ]);

            $usuario->update($request->only('name', 'email', 'phone', 'address', 'role'));
            $this->logActivity('actualizar_usuario', "Actualizó el usuario #{$id}");

            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }
}