<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

#[Group('Login/Logout')]
class AuthController extends Controller
{
    /**
     * Inciar sesión(login) con usuario y contraseña.
     *
     * @param Request $request
     * @return JsonResponse
     * @unauthenticated
     */
    public function login(Request $request)
    {
        $request->validate([
            "username" => ["required", "max:100"],
            "password" => ["required", "max:50"]
        ], [], [
            "username" => "usuario",
            "password" => "contraseña"
        ]);

        $username = Str::lower($request->input("username"));
        $password = $request->input("password");


        $findUser = User::query()
            ->with(["roles"=>fn($q)=>$q->with("permissions")])
            ->where(DB::raw("LOWER(username)"), $username)
            ->first();

        if (empty($findUser) || !Hash::check($password, $findUser->password)) {
            return response()->json([
                "message" => "Credenciales no validas."
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty($findUser->estado)) {
            return response()->json([
                "message" => "Usuario inhabilitado, contacte con soporte o el administrador para activar su cuenta."
            ], Response::HTTP_BAD_REQUEST);
        }

        //eliminar todos los tokens anteriores
        //$findUser->tokens()->delete();

        //nombre del token
        $nameToken = $request->ip()."|".$request->userAgent();

        //crear nuevo token e iniciar sesión
        return response()->json([
            "message" => "Usuario autenticado exitosamente.",
            "token" => $findUser->createToken($nameToken)->plainTextToken,
            "user" => $findUser
        ], Response::HTTP_OK);
    }

    /**
     * Cerrar sesión(logout) del usuario actual.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Obtener el usuario actual de la sesión.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function currentSessionUser(Request $request)
    {
        $user = $request->user();

        if (empty($user->estado) ){
            return response()->json([
                "message" => "Usuario inhabilitado, contacte con soporte o el administrador para activar su cuenta."
            ], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            "user" => $user->load(["roles" =>fn($q) => $q->with(["permissions"])]),
            //"token" => $request->bearerToken()
        ], Response::HTTP_OK);
    }



}
