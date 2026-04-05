<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>¡Bienvenido a SpazioStore!</title>
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
        .header img {
            max-height: 50px;
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
            color: #E91E63;
            font-size: 24px;
            margin-top: 0;
            text-align: center;
        }
        .highlight-text {
            color: #2D3748;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 25px 0;
        }
        .info-box {
            background-color: #F7FAFC;
            border-left: 4px solid #E91E63;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 0 6px 6px 0;
            font-size: 15px;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            background-color: #E91E63;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://lavenderblush-crocodile-665497.hostingersite.com/imagenes/Logo.png" alt="Spazio Cosmetics">
        </div>

        <div class="content">

            @if($role === 'cliente')
                <h2>¡Bienvenido a SpazioStore!</h2>

                <p>Hola, <strong>{{ $name }}</strong>:</p>

                <p>Te damos la más cordial bienvenida a nuestra plataforma. Tu cuenta ha sido creada con éxito y ya eres parte de nuestra comunidad exclusiva.</p>

                <div class="highlight-text">
                    Somos exportadores directos de productos MOOD Italia.
                </div>

                <div class="info-box">
                    En <strong>Spazio Professional Cosmetics</strong> nos dedicamos a traer lo mejor de la cosmética europea a tus manos. Explora nuestro catálogo y descubre productos diseñados para profesionales que exigen calidad y rendimiento.
                </div>

                <p>Ahora que tu registro está completo, puedes acceder a nuestro catálogo exclusivo y disfrutar de las mejores herramientas para tu cuidado personal o tu negocio.</p>

                <div class="button-container">
                    <a href="https://lavenderblush-crocodile-665497.hostingersite.com" class="button">Ir al Catálogo</a>
                </div>

                <p style="margin-top: 30px; text-align: center;">
                    Si tienes alguna duda, no dudes en contactarnos.<br>
                    <strong>El equipo de Spazio Cosmetics</strong>
                </p>
            @else
                <h2>¡Bienvenido al Sistema Administrativo!</h2>

                <p>Hola, <strong>{{ $name }}</strong>:</p>

                <p>Tu cuenta ha sido creada exitosamente en el sistema administrativo de <strong>SpazioStore App</strong>.</p>

                <div class="highlight-text">
                    Rol asignado: {{ ucfirst($role) }}
                </div>

                <div class="info-box">
                    Ya puedes acceder al panel administrativo y utilizar las funciones correspondientes a tu perfil dentro de la plataforma.
                </div>

                <p>
                    Este acceso está destinado a la gestión interna del sistema, incluyendo operaciones administrativas y de despacho según el rol asignado.
                </p>

            <div class="button-container">
                    <a href="spazioapp://login" class="button">Ingresar al Sistema</a>
                </div>

                <p style="margin-top: 30px; text-align: center;">
                    Si necesitas soporte o configuración adicional, comunícate con el administrador del sistema.<br>
                    <strong>Equipo de SpazioStore App</strong>
                </p>
            @endif

        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Spazio Cosmetics. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>