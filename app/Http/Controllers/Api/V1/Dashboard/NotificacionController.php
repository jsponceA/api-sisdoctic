<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificacionController extends Controller
{
    /**
     * Obtener las notificaciones del usuario actual de la sesión.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $notificaciones = $user->unreadNotifications()->take(30)->orderBy("created_at","DESC")->get();
        return response()->json([
            "notificaciones" => $notificaciones,
        ], Response::HTTP_OK);
    }

    /**
     * Marcar una notificación como leída.
     *
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function update(Request $request,int | string $id)
    {
        $user = $request->user();
        $notificacion = $user->unreadNotifications()->where("id",$id)->first();
        if(!empty($notificacion)){
            $notificacion->markAsRead();
            return response()->json([
                "message" => "Notificación marcada como leída.",
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                "message" => "Notificación no encontrada o ya ha sido leída.",
            ], Response::HTTP_NOT_FOUND);
        }

    }
}
