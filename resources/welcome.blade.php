<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOD | API Control Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&family=JetBrains+Mono:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #000000;
            --accent: #E91E63;
            --bg: #050505;
            --card-bg: rgba(17, 17, 17, 0.85);
            --text: #ffffff;
            --green: #00ff88;
            --blue: #00b4ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            perspective: 1000px; /* Añade profundidad */
        }

        /* Fondo de partículas sutil */
        #canvas-bg {
            position: absolute;
            top: 0; left: 0;
            z-index: 0;
            filter: blur(1px);
        }

        /* --- CONTENEDOR PRINCIPAL --- */
        .dashboard {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 900px;
            background: var(--card-bg);
            border: 1px solid rgba(51, 51, 51, 0.5);
            backdrop-filter: blur(10px); /* Efecto cristal */
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.8), 0 0 20px rgba(0, 255, 136, 0.05);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(40px);
        }

        /* --- LOGO ANIMADO --- */
        .logo-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding-right: 30px;
            animation: float 6s ease-in-out infinite; /* Movimiento flotante */
        }

        .logo-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 85px;
            font-weight: 900;
            letter-spacing: -5px;
            position: relative;
            background: linear-gradient(90deg, #fff, #555, #fff, var(--green), #fff);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 4s linear infinite;
        }

        .logo-subtitle {
            font-size: 10px;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: var(--accent);
            margin-top: -10px;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(233, 30, 99, 0.5);
        }

        /* --- STATS --- */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 30px;
            width: 100%;
        }

        .stat-card {
            background: rgba(26, 26, 26, 0.6);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #222;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        /* Stagger de las animaciones de las tarjetas */
        .stat-card:nth-child(1) { animation-delay: 0.3s; }
        .stat-card:nth-child(2) { animation-delay: 0.5s; }
        .stat-card:nth-child(3) { animation-delay: 0.7s; }
        .stat-card:nth-child(4) { animation-delay: 0.9s; }

        /* Efecto Hover en las tarjetas */
        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            border-color: var(--green);
            box-shadow: 0 10px 20px rgba(0, 255, 136, 0.15);
            background: rgba(30, 30, 30, 0.9);
        }

        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 20px;
            color: var(--green);
            display: block;
            text-shadow: 0 0 8px rgba(0, 255, 136, 0.4);
            transition: color 0.3s;
        }

        .stat-card:hover .stat-value {
            color: #fff;
            text-shadow: 0 0 15px var(--green);
        }

        .stat-label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
            display: block;
        }

        /* --- TERMINAL --- */
        .terminal {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 8px;
            padding: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            height: 250px;
            overflow-y: hidden;
            border: 1px solid #333;
            position: relative;
            box-shadow: inset 0 0 20px rgba(0,0,0,1);
        }

        .terminal::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--green), transparent);
            opacity: 0.5;
        }

        .terminal-header {
            color: #666;
            margin-bottom: 15px;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #333;
            padding-bottom: 8px;
        }

        .log-line {
            margin-bottom: 6px;
            opacity: 0;
            transform: translateX(-10px);
            animation: terminalSlideIn 0.3s ease forwards;
        }

        .get { color: var(--green); text-shadow: 0 0 5px var(--green); }
        .post { color: var(--blue); text-shadow: 0 0 5px var(--blue); }
        .path { color: #ccc; }
        .status-ok { color: #fff; background: rgba(0, 255, 136, 0.2); padding: 0 4px; border-radius: 2px; }

        .status-badge {
            margin-top: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--green);
            font-size: 14px;
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.3);
        }

        .dot {
            width: 12px; height: 12px;
            background: var(--green);
            border-radius: 50%;
            box-shadow: 0 0 15px var(--green), 0 0 30px var(--green);
            animation: pulse 1s infinite alternate;
        }

        /* --- KEYFRAMES --- */
        @keyframes shine {
            to { background-position: 300% center; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 0.6; }
            100% { transform: scale(1.2); opacity: 1; }
        }
        @keyframes terminalSlideIn {
            to { opacity: 1; transform: translateX(0); }
        }

        @media (max-width: 768px) {
            .dashboard { grid-template-columns: 1fr; height: auto; margin: 20px; padding: 25px;}
            .logo-section { border-right: none; border-bottom: 1px solid #222; padding-bottom: 30px; padding-right: 0; }
        }
    </style>
</head>
<body>

    <canvas id="canvas-bg"></canvas>

    <div class="dashboard">
        <div class="logo-section">
            <div class="logo-title">MOOD</div>
            <div class="logo-subtitle">Professional API Core</div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-value" id="req-count">14,205</span>
                    <span class="stat-label">Requests</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value">200 OK</span>
                    <span class="stat-label">Status</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value" id="uptime">00:00:00</span>
                    <span class="stat-label">Uptime</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value" id="latency">32ms</span>
                    <span class="stat-label">Latency</span>
                </div>
            </div>

            <div class="status-badge">
                <div class="dot"></div> MOOD STORE - ONLINE
            </div>
        </div>

        <div>
            <div class="terminal">
                <div class="terminal-header">
                    <span>LIVE_API_LOGS <span id="cursor" style="animation: pulse 1s infinite;">_</span></span>
                    <span>SSH: HOSTINGER_PRO</span>
                </div>
                <div id="logs"></div>
            </div>
            <p style="color: #666; font-size: 11px; margin-top: 15px; line-height: 1.6; text-align: justify;">
                Core de Spazio Cosmetic conectado. Sincronización activa con Flutter App v2.4. 
                Base de datos MariaDB optimizada. Secuencias de encriptación TLS 1.3 activas.
            </p>
        </div>
    </div>

    <script>
        // 1. Efecto de fondo (Partículas Mejorado)
        const canvas = document.getElementById('canvas-bg');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5; // Tamaños variados
                this.speedY = Math.random() * 1 + 0.2;
                this.angle = Math.random() * Math.PI * 2;
                this.opacity = Math.random() * 0.6 + 0.1;
            }
            update() {
                this.y -= this.speedY;
                this.x += Math.sin(this.angle) * 0.5; // Movimiento ondulado
                this.angle += 0.02;

                if (this.y < 0) {
                    this.y = canvas.height;
                    this.x = Math.random() * canvas.width;
                }
            }
            draw() {
                ctx.fillStyle = `rgba(0, 255, 136, ${this.opacity})`; // Color verde matrix
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for(let i=0; i<120; i++) particles.push(new Particle());

        function animate() {
            ctx.clearRect(0,0,canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        }
        animate();

        // 2. Simulador de Logs Dinámico
        const logsContainer = document.getElementById('logs');
        const endpoints = [
            {m: 'GET', p: '/api/v1/products'},
            {m: 'POST', p: '/api/v1/auth/login'},
            {m: 'GET', p: '/api/v1/inventory/mood-hair'},
            {m: 'GET', p: '/api/v1/orders/pending'},
            {m: 'POST', p: '/api/v1/payments/confirm'},
            {m: 'PUT', p: '/api/v1/users/profile'},
            {m: 'DEL', p: '/api/v1/cache/clear'}
        ];

        function addLog() {
            const e = endpoints[Math.floor(Math.random() * endpoints.length)];
            const time = new Date().toLocaleTimeString('es-ES', {hour12:false});
            const log = document.createElement('div');
            log.className = 'log-line';
            
            // Variar ligeramente el tiempo de respuesta para mayor realismo
            const ms = Math.floor(Math.random() * 45) + 12; 
            
            log.innerHTML = `
                <span style="color:#555">[${time}]</span> 
                <span class="${e.m.toLowerCase()}">${e.m.padEnd(4, ' ')}</span> 
                <span class="path">${e.p}</span> 
                <span style="float: right;">
                    <span class="status-ok">200 OK</span> 
                    <span style="color:#555; margin-left: 5px;">${ms}ms</span>
                </span>
            `;
            
            logsContainer.appendChild(log);
            
            // Animación suave al eliminar
            if (logsContainer.childNodes.length > 7) {
                logsContainer.removeChild(logsContainer.firstChild);
            }
        }
        // Logs a velocidad variable para mayor realismo
        (function loopLogs() {
            const rand = Math.round(Math.random() * 1500) + 500;
            setTimeout(function() {
                addLog();
                loopLogs();
            }, rand);
        }());

        // 3. Contadores Dinámicos
        let reqs = 14205;
        setInterval(() => {
            reqs += Math.floor(Math.random() * 5);
            document.getElementById('req-count').innerText = reqs.toLocaleString();
            
            // Animar sutilmente la latencia
            document.getElementById('latency').innerText = (Math.floor(Math.random() * 20) + 20) + 'ms';
        }, 2000);

        // 4. Uptime
        let startTime = Date.now();
        setInterval(() => {
            let diff = Date.now() - startTime;
            let h = Math.floor(diff / 3600000).toString().padStart(2, '0');
            let m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
            let s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
            document.getElementById('uptime').innerText = `${h}:${m}:${s}`;
        }, 1000);
    </script>
</body>
</html>