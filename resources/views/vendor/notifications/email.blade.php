<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($actionText) ? $actionText : 'Notificación' }} | {{ config('app.name') }}</title>
    <style type="text/css">
        body { margin: 0; padding: 0; background: #f0f4f8; }
    </style>
</head>
<body style="margin:0; padding:0; background:#f0f4f8;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:560px; width:100%; background:#ffffff; border-radius:16px; box-shadow:0 2px 8px rgba(5,25,35,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="padding:32px 32px 0 32px; text-align:center;">
                            <img src="{{ asset('logo-base.png') }}" alt="Solomon" width="64" height="64"
                                style="width:64px; height:64px; display:block; margin:0 auto 12px;">
                            <h1
                                style="margin:0; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:28px; font-weight:800; letter-spacing:-0.5px; color:#003554; line-height:1.2;">
                                Solomon</h1>
                            <p
                                style="margin:4px 0 0; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:12px; color:#6b7b8d;">
                                Tus hábitos, tu crecimiento</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 0 32px;">
                            <hr style="border:0; border-top:1px solid #d0d8e2; margin:0;">
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding:24px 32px 0 32px;">
                            @if (!empty($greeting))
                                <h2
                                    style="margin:0 0 12px; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:18px; font-weight:700; color:#003554; line-height:1.3;">
                                    {{ $greeting }}</h2>
                            @elseif ($level === 'error')
                                <h2
                                    style="margin:0 0 12px; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:18px; font-weight:700; color:#003554; line-height:1.3;">
                                    @lang('¡Uy!')</h2>
                            @else
                                <h2
                                    style="margin:0 0 12px; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:18px; font-weight:700; color:#003554; line-height:1.3;">
                                    @lang('¡Hola!')</h2>
                            @endif
                        </td>
                    </tr>

                    {{-- Intro Lines --}}
                    <tr>
                        <td style="padding:0 32px 0 32px;">
                            @foreach ($introLines as $line)
                                <p
                                    style="margin:0 0 10px; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:15px; line-height:1.6; color:#051923;">
                                    {{ $line }}</p>
                            @endforeach
                        </td>
                    </tr>

                    {{-- Action Button --}}
                    @isset($actionText)
                        <tr>
                            <td align="center" style="padding:18px 32px 8px 32px;">
                                @php
                                    $buttonColor = match ($level) {
                                        'error' => '#e74c3c',
                                        'success' => '#10b981',
                                        default => '#00a6fb',
                                    };
                                @endphp
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="background:{{ $buttonColor }}; border-radius:10px;">
                                            <a href="{{ $actionUrl }}"
                                                style="display:inline-block; padding:13px 28px; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none;">{{ $actionText }}</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endisset

                    {{-- Outro Lines --}}
                    <tr>
                        <td style="padding:0 32px 0 32px;">
                            @foreach ($outroLines as $line)
                                <p
                                    style="margin:0 0 10px; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:15px; line-height:1.6; color:#051923;">
                                    {{ $line }}</p>
                            @endforeach
                        </td>
                    </tr>

                    {{-- Salutation --}}
                    <tr>
                        <td
                            style="padding:16px 32px 0 32px; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:15px; line-height:1.6; color:#051923;">
                            @if (!empty($salutation))
                                {{ $salutation }}
                            @else
                                @lang('Atentamente,')<br>
                                {{ config('app.name') }}
                            @endif
                        </td>
                    </tr>

                    {{-- Subcopy --}}
                    @isset($actionText)
                        <tr>
                            <td style="padding:20px 32px 8px 32px;">
                                <hr style="border:0; border-top:1px solid #d0d8e2; margin:0 0 14px 0;">
                                <p
                                    style="margin:0; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:12px; line-height:1.6; color:#6b7b8d;">
                                    @lang('¿Tenés problemas para hacer clic en el botón ":actionText"? Copiá y pegá la URL en tu navegador:', ['actionText' => $actionText])
                                    <a href="{{ $actionUrl }}"
                                        style="color:#0582ca; text-decoration:underline; word-break:break-all;">{{ $displayableActionUrl }}</a>
                                </p>
                            </td>
                        </tr>
                    @endisset

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:8px 32px 32px 32px; text-align:center;">
                            <p
                                style="margin:0; font-family:'Instrument Sans', Helvetica, Arial, sans-serif; font-size:11px; color:#6b7b8d;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>