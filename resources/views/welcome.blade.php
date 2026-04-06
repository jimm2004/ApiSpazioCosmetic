<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOD | Spazio Cosmetic API Core</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800;900&family=JetBrains+Mono:wght@300;400&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050505;
            --panel-bg: rgba(18, 18, 18, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #ffffff;
            --text-muted: #8a8a8a;
            --accent-gold: #D4AF37; /* Oro elegante */
            --accent-soft: #f4e8c1;
            --success: #28a745;
            --font-brand: 'Montserrat', sans-serif;
            --font-tech: 'JetBrains Mono', monospace;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: var(--font-brand);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: radial-gradient(circle at 50% 0%, rgba(40,40,40,0.4) 0%, rgba(5,5,5,1) 70%);
        }

        /* --- CONTENEDOR PRINCIPAL --- */
        .dashboard {
            position: relative;
            z-index: 10;
            width: 95%;
            max-width: 1400px;
            height: 85vh;
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255,255,255,0.05);
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1.5fr 1.2fr;
            gap: 30px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* --- COLUMNA 1: MÉTRICAS DEL NEGOCIO --- */
        .stats-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-auto-rows: min-content;
            gap: 15px;
        }

        .stat-card {
            background: rgba(10, 10, 10, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            transition: all 0.4s ease;
        }

        .stat-card:hover {
            border-color: rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

        .stat-label {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
            display: block;
            font-weight: 600;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 400;
            color: var(--text-main);
            font-family: var(--font-tech);
        }

        .color-gold { color: var(--accent-gold); }

        /* --- COLUMNA 2: IDENTIDAD DE MARCA --- */
        .center-col {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            padding: 0 20px;
        }

        .brand-container {
            margin-bottom: 40px;
        }

        /* Recreación exacta del logo */
        .mood-logo {
            font-weight: 900;
            font-size: 110px;
            line-height: 0.9;
            letter-spacing: -4px;
            color: #fff;
            margin-bottom: 5px;
        }

        .mood-subtitle {
            font-weight: 800;
            font-size: 15px;
            letter-spacing: 2px;
            color: #fff;
            text-transform: uppercase;
        }

        .badges {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .badge {
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--text-main);
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.03);
            text-transform: uppercase;
        }

        .badge-dot {
            width: 6px; height: 6px;
            background: var(--success);
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }

        .badge-dot.gold { background: var(--accent-gold); }

        @keyframes pulse-dot {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }

        /* --- COLUMNA 3: TERMINAL Y TRÁFICO --- */
        .right-col {
            display: grid;
            grid-template-rows: 1.5fr 1fr;
            gap: 20px;
        }

        .terminal {
            background: #000;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            font-size: 11px;
            font-family: var(--font-tech);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .term-header {
            color: var(--text-muted);
            border-bottom: 1px solid #222;
            padding-bottom: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            font-family: var(--font-brand);
            font-size: 10px;
            letter-spacing: 1px;
        }

        #logs { flex-grow: 1; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; }
        .log-line { margin-bottom: 6px; white-space: nowrap; color: #ccc; }
        .time { color: #666; margin-right: 8px; }
        .method.get { color: var(--accent-gold); }
        .method.post { color: #fff; font-weight: bold; }
        .method.sync { color: #888; }
        .path { color: #aaa; }
        .status { float: right; color: var(--success); }

        /* Mapa de Tiendas/Tráfico */
        .map-container {
            background: rgba(10, 10, 10, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .map-title {
            position: absolute; top: 15px; left: 15px;
            font-size: 10px; color: var(--text-muted); z-index: 2;
            letter-spacing: 1px; font-weight: 600; text-transform: uppercase;
        }

        /* Simulación de mapa de conexiones elegante */
        .map-grid {
            position: absolute;
            width: 100%; height: 100%;
            background-image: 
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .node {
            position: absolute;
            width: 4px; height: 4px;
            background: var(--text-main);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--text-main);
        }

        .node.active { background: var(--accent-gold); box-shadow: 0 0 15px var(--accent-gold); animation: blink 2s infinite alternate;}

        @keyframes blink { 0% { opacity: 0.3; } 100% { opacity: 1; } }

        .footer-text {
            position: absolute; bottom: 2vh; width: 100%;
            text-align: center; color: var(--text-muted); font-size: 10px; z-index: 10;
            letter-spacing: 1px; text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="dashboard">
        
        <div class="stats-col">
            <div class="stat-card" style="grid-column: span 2;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 15px;">
                    <span style="font-size: 12px; font-weight: 600; letter-spacing: 1px; color: #fff; text-transform: uppercase;">Spazio Cosmetic Core</span>
                    <span style="font-size: 9px; border: 1px solid var(--accent-gold); color: var(--accent-gold); padding: 3px 8px; border-radius: 20px;">SYSTEM SECURE</span>
                </div>
            </div>

            <div class="stat-card">
                <span class="stat-label">Órdenes Hoy</span>
                <span class="stat-value" id="orders-val">124</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Citas Activas</span>
                <span class="stat-value">38</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Sincronización API</span>
                <span class="stat-value color-gold" id="sync-time">0ms</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Inventario Global</span>
                <span class="stat-value">99.8%</span>
            </div>
            <div class="stat-card" style="grid-column: span 2;">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <span class="stat-label">Tráfico E-commerce</span>
                        <span class="stat-value" id="traffic-val">450 req/m</span>
                    </div>
                    <div style="text-align: right;">
                        <span class="stat-label">Estado de Servidores</span>
                        <span class="stat-value" style="color: var(--success); font-size: 14px;">ÓPTIMO</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="center-col">
            <div class="brand-container">
                <div class="mood-logo">MOOD</div>
                <div class="mood-subtitle">Professional Hair Experience</div>
            </div>
            
            <div class="badges">
                <div class="badge">
                    <div class="badge-dot"></div> MOOD STORE VIVO
                </div>
                <div class="badge">
                    <div class="badge-dot gold"></div> RESERVAS SALÓN
                </div>
            </div>
            
            <p style="color: var(--text-muted); font-size: 11px; margin-top: 30px; max-width: 80%; line-height: 1.6;">
                Plataforma centralizada gestionando inventario de productos premium, reservas de estilistas y facturación sincronizada en tiempo real.
            </p>
        </div>

        <div class="right-col">
            <div class="terminal">
                <div class="term-header">
                    <span>REGISTRO DE OPERACIONES</span>
                    <span>v3.4.1</span>
                </div>
                <div id="logs"></div>
            </div>
            
            <div class="map-container">
                <div class="map-grid"></div>
                <div class="map-title">Ruta de Datos // Live</div>
                
                <div class="node active" style="top: 30%; left: 20%;"></div>
                <div class="node" style="top: 50%; left: 35%;"></div>
                <div class="node active" style="top: 40%; left: 55%;"></div>
                <div class="node" style="top: 70%; left: 40%;"></div>
                <div class="node active" style="top: 25%; left: 75%;"></div>
                <div class="node" style="top: 60%; left: 80%;"></div>
                
                <svg style="width: 100%; height: 100%; position: absolute; top:0; left:0; z-index: 1;">
                    <path d="M 60 45 Q 120 70 180 60 T 300 40" fill="none" stroke="rgba(212, 175, 55, 0.3)" stroke-width="1"/>
                    <path d="M 120 70 Q 200 120 280 80" fill="none" stroke="rgba(255, 255, 255, 0.1)" stroke-width="1" stroke-dasharray="2,4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="footer-text">
        Powered by Spazio Cosmetic IT Infrastructure. Sincronización en tiempo real. Todos los derechos reservados.
    </div>

    <script>
        // 1. SIMULADOR DE LOGS ORIENTADO AL SALÓN/ECOMMERCE
        const logsContainer = document.getElementById('logs');
        const endpoints = [
            {m: 'GET', p: '/api/catalog/mood-hair-color', s: '200 OK'},
            {m: 'POST', p: '/api/appointments/new-booking', s: '201 CREATED'},
            {m: 'SYNC', p: '/db/inventory/spazio-warehouse', s: 'SUCCESS'},
            {m: 'GET', p: '/api/stylist/schedule/today', s: '200 OK'},
            {m: 'POST', p: '/ecommerce/checkout/process', s: 'APPROVED'},
            {m: 'SYNC', p: '/pos/physical-store/sales', s: 'UPDATED'}
        ];

        function addLog() {
            const e = endpoints[Math.floor(Math.random() * endpoints.length)];
            const d = new Date();
            const time = `${d.getHours().toString().padStart(2,'0')}:${d.getMinutes().toString().padStart(2,'0')}:${d.getSeconds().toString().padStart(2,'0')}`;
            
            const line = document.createElement('div');
            line.className = 'log-line';
            line.innerHTML = `
                <span class="time">[${time}]</span> 
                <span class="method ${e.m.toLowerCase()}">${e.m}</span> 
                <span class="path">${e.p}</span> 
                <span class="status" style="color: ${e.s.includes('SUCCESS') || e.s.includes('APPROVED') ? 'var(--accent-gold)' : 'var(--success)'}">${e.s}</span>
            `;
            
            logsContainer.appendChild(line);
            if(logsContainer.childElementCount > 10) {
                logsContainer.removeChild(logsContainer.firstChild);
            }
        }
        setInterval(addLog, 1200);

        // 2. ACTUALIZACIÓN DE MÉTRICAS
        let orders = 124;
        setInterval(() => {
            if(Math.random() > 0.7) { // Aumentar órdenes esporádicamente
                orders += 1;
                document.getElementById('orders-val').innerText = orders;
            }
        }, 3000);

        setInterval(() => {
            const traffic = Math.floor(Math.random() * (480 - 420) + 420);
            document.getElementById('traffic-val').innerText = `${traffic} req/m`;
        }, 4000);

        setInterval(() => {
            const ping = Math.floor(Math.random() * (45 - 12) + 12);
            document.getElementById('sync-time').innerText = `${ping}ms`;
        }, 2000);
    </script>
</body>
</html>