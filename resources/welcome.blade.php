<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOD | API Backend</title>
    <style>
        /* Importamos fuentes elegantes de Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@900&family=Roboto:wght@400;500&display=swap');

        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background: white;
            padding: 50px 40px;
            border-radius: 8px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 90%;
            animation: fadeIn 0.8s ease-out;
            border-top: 6px solid #000000;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Estilos específicos para recrear el Logo MOOD */
        .logo-container {
            margin-bottom: 35px;
            user-select: none;
        }
        .logo-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 72px;
            letter-spacing: -3px;
            color: #000000;
            margin: 0;
            line-height: 1;
        }
        .logo-subtitle {
            font-family: 'Roboto', sans-serif;
            font-weight: 500;
            font-size: 11px;
            letter-spacing: 4px;
            color: #000000;
            text-transform: uppercase;
            margin-top: 5px;
        }

        /* Textos descriptivos */
        h2 {
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
            color: #222;
        }
        p {
            color: #666;
            margin-bottom: 35px;
            line-height: 1.6;
            font-size: 15px;
        }
        
        /* Botón de Estado */
        .badge {
            display: inline-flex;
            align-items: center;
            background-color: #000000;
            color: #ffffff;
            padding: 10px 22px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #00e676; /* Verde neón brillante */
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 0 8px rgba(0, 230, 118, 0.6);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="logo-container">
            <h1 class="logo-title">MOOD</h1>
            <div class="logo-subtitle">Professional Hair Experience</div>
        </div>

        <h2>API Central Core</h2>
        <p>El servidor Backend está funcionando correctamente. Los servicios de catálogo, inventario y endpoints de la tienda están listos para recibir peticiones.</p>
        
        <div class="badge">
            <span class="status-dot"></span> SISTEMA EN LÍNEA
        </div>
        
    </div>
</body>
</html>