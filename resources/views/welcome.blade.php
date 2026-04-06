<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOD | Spazio Cosmetic API Core</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800;900&family=JetBrains+Mono:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050505;
            --panel-bg: rgba(18, 18, 18, 0.65);
            --border-color: rgba(255, 255, 255, 0.08);
            --border-glow: rgba(212, 175, 55, 0.3);
            --text-main: #ffffff;
            --text-muted: #8a8a8a;
            --accent-gold: #D4AF37; 
            --accent-soft: #f4e8c1;
            --success: #28a745;
            --danger: #dc3545;
            --font-brand: 'Montserrat', sans-serif;
            --font-tech: 'JetBrains Mono', monospace;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: var(--font-brand);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Fondo Animado Dinámico */
            background: radial-gradient(circle at 50% 0%, rgba(40,40,40,0.5) 0%, rgba(5,5,5,1) 70%), 
                        linear-gradient(45deg, #050505, #0a0a0a, #111, #050505);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            padding: 20px;
            overflow-x: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* --- CONTENEDOR PRINCIPAL --- */
        .dashboard {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1400px;
            min-height: 85vh;
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255,255,255,0.05);
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1.5fr 1.2fr;
            gap: 30px;
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
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
            border-radius: 10px;
            padding: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            transform: skewX(-20deg);
            transition: 0.5s;
        }

        .stat-card:hover::before { left: 150%; }
        .stat-card:hover {
            border-color: var(--accent-gold);
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.15);
        }

        .span-2 { grid-column: span 2; }

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
            font-size: 24px;
            font-weight: 400;
            color: var(--text-main);
            font-family: var(--font-tech);
            text-shadow: 0 0 10px rgba(255,255,255,0.2);
        }

        .color-gold { color: var(--accent-gold); text-shadow: 0 0 15px rgba(212, 175, 55, 0.4); }

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
            position: relative;
        }

        .brand-container { margin-bottom: 40px; animation: pulseGlow 4s infinite alternate; }

        @keyframes pulseGlow {
            0% { filter: drop-shadow(0 0 10px rgba(255,255,255,0.05)); }
            100% { filter: drop-shadow(0 0 20px rgba(212, 175, 55, 0.2)); }
        }

        .mood-logo {
            font-weight: 900;
            font-size: clamp(60px, 8vw, 110px);
            line-height: 0.9;
            letter-spacing: -4px;
            background: linear-gradient(to bottom right, #ffffff, #aaaaaa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .mood-subtitle {
            font-weight: 800;
            font-size: clamp(10px, 1.5vw, 15px);
            letter-spacing: 2px;
            color: #fff;
            text-transform: uppercase;
        }

        .badges { display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap; justify-content: center; }

        .badge {
            border: 1px solid rgba(255,255,255,0.2);
            color: var(--text-main);
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.03);
            text-transform: uppercase;
            transition: all 0.3s ease;
            cursor: default;
        }

        .badge:hover { background: rgba(255, 255, 255, 0.08); border-color: var(--accent-gold); }

        .badge-dot {
            width: 6px; height: 6px;
            background: var(--success);
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }
        .badge-dot.gold { background: var(--accent-gold); animation: pulse-dot-gold 2s infinite; }

        @keyframes pulse-dot {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.6); }
            70% { box-shadow: 0 0 0 8px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        @keyframes pulse-dot-gold {
            0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.6); }
            70% { box-shadow: 0 0 0 8px rgba(212, 175, 55, 0); }
            100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
        }

        /* --- COLUMNA 3: TERMINAL Y TRÁFICO --- */
        .right-col { display: grid; grid-template-rows: 1.5fr 1fr; gap: 20px; }

        .terminal {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 15px;
            font-size: 11px;
            font-family: var(--font-tech);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: inset 0 0 20px rgba(0,0,0,1);
            position: relative;
        }

        /* Scanline effect para la terminal */
        .terminal::after {
            content: " ";
            display: block; position: absolute; top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            z-index: 2; background-size: 100% 2px, 3px 100%; pointer-events: none;
        }

        .term-header {
            color: var(--text-muted);
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            font-family: var(--font-brand);
            font-size: 10px;
            letter-spacing: 1px;
        }

        #logs { flex-grow: 1; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; z-index: 3;}
        .log-line { margin-bottom: 6px; white-space: nowrap; color: #ccc; animation: slideInLeft 0.3s ease forwards; }
        
        @keyframes slideInLeft {
            from { transform: translateX(-10px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .time { color: #666; margin-right: 5px; }
        .ip-addr { color: #5dade2; font-weight: bold; margin-right: 8px; background: rgba(93, 173, 226, 0.1); padding: 1px 4px; border-radius: 3px;}
        .method.get { color: var(--accent-gold); }
        .method.post { color: #fff; font-weight: bold; }
        .method.sync { color: #888; }
        .path { color: #aaa; }
        .status { float: right; font-weight: bold;}

        /* Mapa de Tiendas/Tráfico */
        .map-container {
            background: rgba(10, 10, 10, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 10px;
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

        .map-grid {
            position: absolute; width: 100%; height: 100%;
            background-image: 
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: moveGrid 20s linear infinite;
        }

        @keyframes moveGrid {
            from { transform: translateY(0); }
            to { transform: translateY(30px); }
        }

        .node {
            position: absolute;
            width: 6px; height: 6px;
            background: var(--text-muted);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--text-muted);
            transition: all 0.3s ease;
            z-index: 5;
        }

        .node.active { 
            background: var(--accent-gold); 
            box-shadow: 0 0 15px var(--accent-gold), 0 0 30px var(--accent-gold); 
            transform: scale(1.5);
        }
        
        .node.ping {
            background: var(--success);
            box-shadow: 0 0 20px var(--success);
        }

        .footer-text {
            position: fixed; bottom: 15px; width: 100%;
            text-align: center; color: rgba(255,255,255,0.2); font-size: 9px; z-index: 10;
            letter-spacing: 2px; text-transform: uppercase;
            pointer-events: none;
        }

        /* =========================================
           RESPONSIVE DESIGN (MOBILE FIRST & TABLET)
           ========================================= */
        @media (max-width: 1024px) {
            .dashboard {
                grid-template-columns: 1fr 1fr;
                height: auto;
                padding: 30px;
            }
            .center-col {
                grid-column: span 2;
                order: -1; /* Pone el logo arriba */
                border: none;
                border-bottom: 1px solid var(--border-color);
                padding-bottom: 30px;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 768px) {
            body { padding: 10px; height: auto; display: block; }
            .dashboard {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px;
            }
            .center-col { grid-column: span 1; }
            .stats-col { grid-template-columns: 1fr; }
            .span-2 { grid-column: span 1; } /* Rompe las tarjetas anchas en mobile */
            .terminal { min-height: 250px; }
            .map-container { min-height: 200px; }
            
            .stat-card { padding: 15px; }
            .mood-logo { font-size: 65px; }
            .badges { flex-direction: column; align-items: center; gap: 10px; }
            .badge { width: 100%; justify-content: center; }
            
            .footer-text { position: relative; margin-top: 20px; bottom: 0; }
        }
    </style>
</head>
<body>

    <div class="dashboard">
        
        <div class="stats-col">
            <div class="stat-card span-2">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; margin-bottom: 15px;">
                    <span style="font-size: 12px; font-weight: 800; letter-spacing: 1px; color: #fff; text-transform: uppercase;">Spazio Cosmetic Core</span>
                    <span style="font-size: 9px; border: 1px solid var(--accent-gold); color: var(--accent-gold); padding: 4px 10px; border-radius: 20px; font-weight: 600; box-shadow: 0 0 10px rgba(212,175,55,0.2);">SYSTEM SECURE</span>
                </div>
            </div>

            <div class="stat-card">
                <span class="stat-label">Órdenes Hoy</span>
                <span class="stat-value" id="orders-val">124</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Citas Activas</span>
                <span class="stat-value" id="citas-val">38</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Sincronización API</span>
                <span class="stat-value color-gold" id="sync-time">12ms</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Inventario Global</span>
                <span class="stat-value" id="inventory-val">99.8%</span>
            </div>
            <div class="stat-card span-2">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <span class="stat-label">Tráfico E-commerce</span>
                        <span class="stat-value" id="traffic-val">450 req/m</span>
                    </div>
                    <div style="text-align: right;">
                        <span class="stat-label">Estado de Servidores</span>
                        <span class="stat-value" style="color: var(--success); font-size: 16px; font-weight: bold; text-shadow: 0 0 10px rgba(40,167,69,0.4);">ÓPTIMO</span>
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
            
            <p style="color: var(--text-muted); font-size: 12px; margin-top: 35px; max-width: 90%; line-height: 1.8; font-weight: 300;">
                Plataforma centralizada gestionando inventario de productos premium, reservas de estilistas y facturación sincronizada en <strong style="color:#fff">tiempo real</strong>.
            </p>
        </div>

        <div class="right-col">
            <div class="terminal">
                <div class="term-header">
                    <span>REGISTRO DE RED DEDICADA</span>
                    <span style="color: var(--accent-gold);">v3.5.0-LIVE</span>
                </div>
                <div id="logs"></div>
            </div>
            
            <div class="map-container" id="map-area">
                <div class="map-grid"></div>
                <div class="map-title">Ruta de Datos // Live Geo</div>
                
                <div class="node" style="top: 30%; left: 20%;" id="node-1"></div>
                <div class="node" style="top: 50%; left: 35%;" id="node-2"></div>
                <div class="node" style="top: 40%; left: 55%;" id="node-3"></div>
                <div class="node" style="top: 70%; left: 40%;" id="node-4"></div>
                <div class="node" style="top: 25%; left: 75%;" id="node-5"></div>
                <div class="node" style="top: 60%; left: 80%;" id="node-6"></div>
                
                <svg style="width: 100%; height: 100%; position: absolute; top:0; left:0; z-index: 1;">
                    <path class="svg-line" d="M 20% 30% Q 35% 50% 55% 40%" fill="none" stroke="rgba(212, 175, 55, 0.2)" stroke-width="1.5" stroke-dasharray="4,4"/>
                    <path class="svg-line" d="M 55% 40% Q 75% 25% 80% 60%" fill="none" stroke="rgba(255, 255, 255, 0.1)" stroke-width="1"/>
                    <path class="svg-line" d="M 35% 50% Q 40% 70% 80% 60%" fill="none" stroke="rgba(40, 167, 69, 0.2)" stroke-width="1" stroke-dasharray="2,6"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="footer-text">
        Powered by Spazio Cosmetic IT Infrastructure. Sincronización en tiempo real. Todos los derechos reservados.
    </div>

    <script>
        // 1. GENERADOR DE IPS DINÁMICAS
        const generateIP = () => {
            const segments = [
                Math.floor(Math.random() * (220 - 10) + 10), // Evitar 0.x y broadcast
                Math.floor(Math.random() * 255),
                Math.floor(Math.random() * 255),
                Math.floor(Math.random() * 255)
            ];
            // Simular algunas IPs locales (tiendas) vs públicas (ecommerce)
            if(Math.random() > 0.7) return `192.168.1.${Math.floor(Math.random()*100)+10}`;
            return segments.join('.');
        };

        // 2. SIMULADOR DE LOGS ORIENTADO AL SALÓN/ECOMMERCE
        const logsContainer = document.getElementById('logs');
        const endpoints = [
            {m: 'GET', p: '/api/catalog/mood-color', s: '200 OK', c: 'var(--success)'},
            {m: 'POST', p: '/api/appointments/new', s: '201 CREATED', c: 'var(--success)'},
            {m: 'SYNC', p: '/db/inventory/warehouse', s: 'SUCCESS', c: 'var(--accent-gold)'},
            {m: 'GET', p: '/api/stylist/schedule', s: '200 OK', c: 'var(--success)'},
            {m: 'POST', p: '/ecommerce/checkout', s: 'APPROVED', c: 'var(--accent-gold)'},
            {m: 'SYNC', p: '/pos/physical-store', s: 'UPDATED', c: 'var(--success)'},
            {m: 'WARN', p: '/api/gateway/auth', s: 'RETRYING', c: '#ffc107'}
        ];

        function addLog() {
            const e = endpoints[Math.floor(Math.random() * endpoints.length)];
            const d = new Date();
            const time = `${d.getHours().toString().padStart(2,'0')}:${d.getMinutes().toString().padStart(2,'0')}:${d.getSeconds().toString().padStart(2,'0')}`;
            const ip = generateIP();
            
            const line = document.createElement('div');
            line.className = 'log-line';
            line.innerHTML = `
                <span class="time">[${time}]</span> 
                <span class="ip-addr">${ip}</span>
                <span class="method ${e.m.toLowerCase()}">${e.m}</span> 
                <span class="path">${e.p}</span> 
                <span class="status" style="color: ${e.c}">${e.s}</span>
            `;
            
            logsContainer.appendChild(line);
            
            // Límite de logs para no saturar DOM (responsivo)
            const maxLogs = window.innerWidth < 768 ? 6 : 10;
            if(logsContainer.childElementCount > maxLogs) {
                logsContainer.removeChild(logsContainer.firstChild);
            }

            // Animar un nodo del mapa al azar cuando hay un log
            animateRandomNode();
        }
        
        // Intervalo de logs variable para mayor realismo
        (function loopLogs() {
            const rand = Math.round(Math.random() * (2000 - 500)) + 500;
            setTimeout(function() {
                addLog();
                loopLogs();  
            }, rand);
        }());

        // 3. ANIMACIÓN DE MAPA DINÁMICO
        const nodes = [1, 2, 3, 4, 5, 6].map(i => document.getElementById(`node-${i}`));
        function animateRandomNode() {
            const node = nodes[Math.floor(Math.random() * nodes.length)];
            
            // Remover clases previas
            node.classList.remove('active', 'ping');
            
            // Asignar estado aleatorio (oro o verde)
            const isPing = Math.random() > 0.5;
            node.classList.add(isPing ? 'ping' : 'active');

            setTimeout(() => {
                node.classList.remove('active', 'ping');
            }, 800); // Duración del destello
        }

        // 4. ACTUALIZACIÓN DE MÉTRICAS SUAVES
        let orders = 124;
        let citas = 38;
        
        setInterval(() => {
            if(Math.random() > 0.7) { 
                orders += 1;
                const ordEl = document.getElementById('orders-val');
                ordEl.innerText = orders;
                ordEl.style.color = "var(--accent-gold)";
                setTimeout(() => ordEl.style.color = "var(--text-main)", 300);
            }
            if(Math.random() > 0.9) { 
                citas += 1;
                document.getElementById('citas-val').innerText = citas;
            }
        }, 3000);

        setInterval(() => {
            const traffic = Math.floor(Math.random() * (480 - 410) + 410);
            document.getElementById('traffic-val').innerText = `${traffic} req/m`;
        }, 2500);

        setInterval(() => {
            const ping = Math.floor(Math.random() * (45 - 8) + 8);
            const syncEl = document.getElementById('sync-time');
            syncEl.innerText = `${ping}ms`;
            // Cambiar a rojo si hay "latencia" alta
            syncEl.style.color = ping > 40 ? "var(--danger)" : "var(--accent-gold)";
        }, 1500);
        
        // Actualizar inventario ocasionalmente
        setInterval(() => {
            const inv = (99.5 + Math.random() * 0.4).toFixed(2);
            document.getElementById('inventory-val').innerText = `${inv}%`;
        }, 10000);

    </script>
</body>
</html>