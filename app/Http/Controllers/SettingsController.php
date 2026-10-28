<?php

namespace App\Http\Controllers;

use App\Contracts\WhatsAppGateway;
use App\Support\PhoneNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Throwable;

class SettingsController extends Controller
{
    public function settings()
    {
        return view('settings.settings');
    }

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'proverbios' => ['required', 'boolean'],
            'frases' => ['required', 'boolean'],
        ]);

        $contenido = match (true) {
            $data['proverbios'] && $data['frases'] => 'Ambos',
            (bool) $data['proverbios'] => 'Proverbios',
            (bool) $data['frases'] => 'Frases',
            default => 'Ninguno',
        };

        $request->user()->updatePreferences(['ContenidoMotivacional' => $contenido]);

        return response()->json(['contenido' => $contenido]);
    }

    public function updatePhone(Request $request): JsonResponse
    {
        $request->merge(['telefono' => PhoneNumber::normalize($request->input('telefono'))]);

        $data = $request->validate([
            'telefono' => ['nullable', 'string', 'max:20', 'regex:'.PhoneNumber::E164_REGEX],
        ]);

        $request->user()->update(['telefono' => $data['telefono'] ?? null]);

        return response()->json(['telefono' => $data['telefono'] ?? null]);
    }

    public function testWhatsApp(Request $request, WhatsAppGateway $gateway): JsonResponse
    {
        $user = $request->user();

        if (! $user->telefono) {
            return response()->json([
                'message' => 'Configurá un número de WhatsApp antes de probar.',
            ], 422);
        }

        $habitCount = $user->habitos()->count();
        $message = "✅ Prueba de recordatorios de hábitos de Solomon.\nHábitos configurados: {$habitCount}.";

        try {
            $gateway->send($user->telefono, $message);
        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'No se pudo enviar la prueba: '.Str::limit($exception->getMessage(), 300),
            ], 502);
        }

        return response()->json([
            'message' => 'Mensaje de prueba enviado a '.$user->telefono.'.',
        ]);
    }

    public function updateBiography(Request $request)
    {
        $data = $request->validate(['biografia' => ['required', 'string', 'max:300']]);

        $request->user()->updateBiography($data['biografia']);
    }
}
