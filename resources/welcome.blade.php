<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spazio Cosmetic | API Backend</title>
    <style>
        body {
            background-color: #f4f7f6;
            color: #333;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            max-width: 450px;
            width: 90%;
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon {
            width: 70px;
            height: 70px;
            margin-bottom: 20px;
            fill: none;
            stroke: #E91E63; /* El color rosa de Spazio */
            stroke-width: 1.5;
        }
        h1 {
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 10px;
            color: #2d3748;
        }
        p {
            color: #718096;
            margin-bottom: 25px;
            line-height: 1.6;
            font-size: 15px;
        }
        .badge {
            display: inline-block;
            background-color: #e6fffa;
            color: #319795;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM9 6h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <h1>API Spazio Cosmetic</h1>
        <p>El servidor Backend de MoodStore está funcionando correctamente. Los servicios de catálogo, inventario y cobros están listos para conectarse con la App de Flutter.</p>
        
        <div class="badge">
            ● SISTEMA EN LÍNEA
        </div>
    </div>
</body>
</html>