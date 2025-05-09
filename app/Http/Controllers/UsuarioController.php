<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $usuarios = User::where('name', 'LIKE', "%{$search}%")->get();
        } else {
            $usuarios = User::all();
        }

        // Si la solicitud es AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json($usuarios);
        }

        return view('usuarios', compact('usuarios'));
    }
}
