<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            padding: 20px;
            margin: 30px auto;
            width: 600px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px;
        }
        .header img {
            width: 150px;
            height: auto;
        }
        .header-title {
            background-color: #34447C;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
        .content {
            padding: 20px;
            color: #333;
        }
        .content h1 {
            color: #333;
            font-size: 22px;
        }
        .content p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <!-- Incluir la imagen del logo -->
            <img src="{{ $message->embed(public_path('logo.png')) }}" alt="Logo de la empresa">
        </div>
        <div class="header-title">
            AGBC SENCA 
        </div>
        <div class="content">
            <h1>¡Hola, {{ $cliente->nombre }}!</h1>
            <p>Su alquiler de la casilla número {{ $casilla->nombre }} vence hoy, {{ \Carbon\Carbon::parse($alquilere->fin_fecha)->format('d/m/Y') }}.</p>
            <p>Por favor, acérquese a la ventanilla 32 para realizar la renovación correspondiente.</p>
            <p>Gracias por utilizar nuestros servicios.</p>
        </div>
        <div class="footer">
            AGBC,<br>
            SENCA 
        </div>
    </div>
</body>
</html>
