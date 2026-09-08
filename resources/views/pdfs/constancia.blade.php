<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia Laboral - {{ $empleado->nombres }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .date {
            text-align: right;
            margin-bottom: 50px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 40px;
            text-decoration: underline;
        }
        .content {
            text-align: justify;
            margin-bottom: 50px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .signature-area {
            text-align: center;
            margin-top: 100px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 300px;
            margin: 0 auto 10px auto;
        }
        .footer {
            position: absolute;
            bottom: 30px;
            width: 100%;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $empleado->empresa->nombre ?? 'Nuestra Corporación' }}</h1>
    </div>

    <div class="date">
        Guatemala, {{ $fecha }}
    </div>

    <div class="title">
        A QUIEN INTERESE
    </div>

    <div class="content">
        <p>Por medio de la presente, el Departamento de Recursos Humanos hace constar que el(la) señor(a) <strong>{{ $empleado->nombres }} {{ $empleado->apellidos }}</strong>, labora para nuestra empresa <strong>{{ $empleado->empresa->nombre ?? 'Nuestra Corporación' }}</strong>.</p>

        <p>El(la) empleado(a) se encuentra registrado(a) en nuestros sistemas bajo el código de colaborador <strong>{{ $empleado->codigo_empleado }}</strong>, demostrando durante su tiempo de servicio responsabilidad, honestidad y dedicación en las labores encomendadas.</p>

        <p>Se extiende la presente constancia laboral a petición de la parte interesada, para los usos legales o personales que a él/ella convengan.</p>
    </div>

    <div class="signature-area">
        <div class="signature-line"></div>
        <strong>Departamento de Recursos Humanos</strong><br>
        {{ $empleado->empresa->nombre ?? 'Nuestra Corporación' }}
    </div>

    <div class="footer">
        Documento generado automáticamente por el Sistema de Control de Planillas.
    </div>

</body>
</html>