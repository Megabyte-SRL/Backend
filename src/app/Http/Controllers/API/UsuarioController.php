<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActualizarUsuarioRequest;
use App\Http\Requests\IniciarSesionUsuarioRequest;
use App\Http\Requests\GuardarUsuarioRequest;
use App\Http\Resources\UserAuthenticatedShowResource;
use App\Http\Resources\UsuarioShowResource;
use App\Models\Usuario;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    /**
     * Iniciamos sesión de un usuario
     *
     * @return \Illuminate\Http\Response
     */
    public function login(IniciarSesionUsuarioRequest $request)
    {
        $user = Usuario::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('authToken')->plainTextToken;
        $user->token = $token;
        if ($user->rol === 'admin') {
            $user->nombre = 'Administrador Administrador';
        } elseif ($user->rol === 'docente') {
            $docente = Docente::where('usuario_id', $user->id)->first();
            $user->nombre = $docente->nombre . ' ' . $docente->apellido;
        }
        return UserAuthenticatedShowResource::make($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuardarUsuarioRequest $request)
    {
        try {
            Usuario::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'rol' => $request->rol,
            ]);

            return response()->json([
                'status' => 201,
                'res' => true,
                'msg' => 'Usuario registrado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'res' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener información de usuario
     *
     */
    public function obtenerInformacion()
    {
        $usuario = Auth::user();
        return new UsuarioShowResource($usuario);
    }

    /**
     * Actualizar información de usuario
     */
    public function actualizarInformacion(ActualizarUsuarioRequest $request)
    {
        $user = Auth::user();

        // Retrieve the validated input data
        $validatedData = $request->validated();

        // Update user data in the 'usuarios' table
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // If the user is a 'docente', update the 'docentes' table
        if ($user->rol === 'docente') {
            $docente = Docente::where('usuario_id', $user->id)->first();
            if (!$docente) {
                return response()->json(['message' => 'Docente not found'], 404);
            }
            $docente->nombre = $validatedData['nombre'];
            $docente->apellido = $validatedData['apellido'];
            $docente->save();
        }

        return response()->json(['msg' => 'Usuario actualizada correctamente'], 200);
    }
}
