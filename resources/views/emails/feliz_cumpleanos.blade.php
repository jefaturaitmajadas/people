<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Feliz Cumpleaños!</title>
</head>
@php
    // Logo: incrustado en el correo cuando se envía, URL pública en la vista previa
    $logoSrc = null;
    if ($empresa && $empresa->logo) {
        $rutaLogo = storage_path('app/public/' . $empresa->logo);
        if (isset($message) && file_exists($rutaLogo)) {
            $logoSrc = $message->embed($rutaLogo);
        } else {
            $logoSrc = asset('storage/' . $empresa->logo);
        }
    }
    $banderines = ['#E9A23B', '#E5737F', '#8E90D6'];
@endphp
<body style="margin: 0; padding: 0; background-color: #F3EEE3;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #F3EEE3;">
        <tr>
            <td align="center" style="padding: 24px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px; background-color: #F8EBCB; border-radius: 12px; overflow: hidden;">

                    <!-- Banderines -->
                    <tr>
                        <td style="padding: 0; border-top: 6px solid #4C5BB0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    @foreach(str_split('HAPPYBIRTHDAY') as $i => $letra)
                                        <td align="center" style="padding: 0 2px;">
                                            <div style="background-color: {{ $banderines[$i % 3] }}; color: #FFFFFF; font-family: Arial, sans-serif; font-weight: bold; font-size: 18px; line-height: 40px; height: 44px; border-radius: 0 0 22px 22px;">{{ $letra }}</div>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Título -->
                    <tr>
                        <td align="center" style="padding: 36px 30px 6px; font-family: 'Brush Script MT', 'Segoe Script', cursive; font-size: 54px; line-height: 1.1; color: #4C5BB0;">
                            ¡Feliz Cumpleaños!
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 44px; line-height: 1.2; padding-bottom: 6px;">🎂🎈🎉</td>
                    </tr>

                    <!-- Mensaje -->
                    <tr>
                        <td style="padding: 18px 40px 10px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #FFFFFF; border: 3px dotted #7F84D6;">
                                <tr>
                                    <td align="center" style="padding: 32px 28px; font-family: Arial, Helvetica, sans-serif; color: #2E2E3A;">
                                        <p style="margin: 0 0 6px; font-size: 16px; color: #6B6B80;">Querido(a)</p>
                                        <p style="margin: 0 0 18px; font-size: 28px; font-weight: bold; color: #4C5BB0;">{{ $empleado->nombre_corto }}</p>
                                        <p style="margin: 0 0 14px; font-size: 16px; line-height: 1.6;">
                                            Hoy celebramos contigo un año más de vida. Gracias por tu esfuerzo, dedicación y por ser parte de nuestro equipo.
                                        </p>
                                        <p style="margin: 0; font-size: 16px; line-height: 1.6;">
                                            Te deseamos un día lleno de alegría, salud y bendiciones junto a las personas que más quieres.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 18px 30px 6px; font-family: 'Brush Script MT', 'Segoe Script', cursive; font-size: 44px; color: #4C5BB0;">
                            ¡Felicidades!
                        </td>
                    </tr>

                    <!-- Firma / Logo -->
                    <tr>
                        <td align="center" style="padding: 10px 30px 8px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #6B6B80;">
                            Con cariño, tu familia de {{ $empresa->nombre ?? 'la empresa' }}
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 30px 20px;">
                            @if($logoSrc)
                                <img src="{{ $logoSrc }}" alt="{{ $empresa->nombre }}" style="max-height: 90px; max-width: 200px; height: auto; border: 0;">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-size: 38px; line-height: 1.2; padding-bottom: 24px;">🎈🎈🎈</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
