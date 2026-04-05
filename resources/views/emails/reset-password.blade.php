<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            background-color: #F0F4F8;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #ffffff;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #eeeeee;
        }
        /* Estilos para el logo */
        .header img {
            max-height: 50px; /* Ajusta esta altura según prefieras tu logo */
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }
        .content {
            padding: 40px 30px;
            color: #4A5568;
            font-size: 16px;
            line-height: 1.6;
        }
        .content h2 {
            color: #2D3748;
            font-size: 20px;
            margin-top: 0;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            background-color: #323B4C; /* Color oscuro elegante */
            color: #ffffff !important;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #a0aec0;
            border-top: 1px solid #eeeeee;
        }
        .sub-text {
            font-size: 13px;
            color: #718096;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
<div class="header">
            <img src="{{ asset('imagenes/Logo.png') }}" alt="Spazio Cosmetics">
        </div>

        <div class="content">
            <h2>¡Hola!</h2>
            <p>Recibes este correo electrónico porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Spazio Cosmetics</strong>.</p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Restablecer Contraseña</a>
            </div>
            
            <p>Este enlace para restablecer la contraseña caducará en 5 minutos.</p>
            <p>Si no realizaste esta solicitud, no es necesario realizar ninguna otra acción. Tu cuenta está segura.</p>
            
            <p style="margin-top: 30px;">
                Saludos,<br>
                El equipo de Spazio Cosmetics
            </p>


        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Spazio Cosmetics. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>