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
            --bg: #0a0a0a;
            --card-bg: #111111;
            --text: #ffffff;
            --green: #00ff88;
        }

        * { margin: 0; padding: 0; box-box: border-box; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Fondo de partículas sutil */
        #canvas-bg {
            position: absolute;
            top: 0; left: 0;
            z-index: 0;
        }

        .dashboard {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 900px;
            background: var(--card-bg);
            border: 1px solid #333;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* --- LOGO ANIMADO --- */
        .logo-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-right: 1px solid #222;
            padding-right: 30px;
        }

        .logo-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 85px;
            font-weight: 900;
            letter-spacing: -5px;
            position: relative;
            background: linear-gradient(90deg, #fff, #444, #fff);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s linear infinite;
        }

        @keyframes shine {
            to { background-position: 200% center; }
        }

        .logo-subtitle {
            font-size: 10px;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: var(--accent);
            margin-top: -10px;
            font-weight: 700;
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
            background: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #222;
        }

        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 20px;
            color: var(--green);
            display: block;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        /* --- TERMINAL --- */
        .terminal {
            background: #000;
            border-radius: 8px;
            padding: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            height: 250px;
            overflow-y: hidden;
            border: 1px solid #222;
            position: relative;
        }

        .terminal-header {
            color: #444;
            margin-bottom: 10px;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
        }

        .log-line {
            margin-bottom: 5px;
            animation: typing 0.2s steps(20, end);
        }

        .get { color: var(--green); }
        .post { color: #00b4ff; }
        .path { color: #eee; }

        .status-badge {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--green);
            font-size: 14px;
        }

        .dot {
            width: 10px; height: 10px;
            background: var(--green);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--green);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 1; }
            100% { opacity: 0.4; }
        }

        @media (max-width: 768px) {
            .dashboard { grid-template-columns: 1fr; height: auto; margin: 20px; }
            .logo-section { border-right: none; border-bottom: 1px solid #222; padding-bottom: 30px; }
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
                    <span class="stat-value">32ms</span>
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
                    <span>LIVE_API_LOGS</span>
                    <span>SSH: HOSTINGER_PRO</span>
                </div>
                <div id="logs"></div>
            </div>
            <p style="color: #444; font-size: 11px; margin-top: 15px; line-height: 1.4;">
                Core de Spazio Cosmetic conectado. Sincronización activa con Flutter App v2.4. 
                Base de datos MariaDB optimizada.
            </p>
        </div>
    </div>

    <script>
        // 1. Efecto de fondo (Partículas)
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
                this.speed = Math.random() * 0.5 + 0.1;
                this.opacity = Math.random() * 0.5;
            }
            update() {
                this.y -= this.speed;
                if (this.y < 0) this.y = canvas.height;
            }
            draw() {
                ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.fillRect(this.x, this.y, 1, 1);
            }
        }

        for(let i=0; i<100; i++) particles.push(new Particle());

        function animate() {
            ctx.clearRect(0,0,canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        }
        animate();

        // 2. Simulador de Logs
        const logsContainer = document.getElementById('logs');
        const endpoints = [
            {m: 'GET', p: '/api/v1/products'},
            {m: 'POST', p: '/api/v1/auth/login'},
            {m: 'GET', p: '/api/v1/inventory/mood-hair'},
            {m: 'GET', p: '/api/v1/orders/pending'},
            {m: 'POST', p: '/api/v1/payments/confirm'}
        ];

        function addLog() {
            const e = endpoints[Math.floor(Math.random() * endpoints.length)];
            const time = new Date().toLocaleTimeString();
            const log = document.createElement('div');
            log.className = 'log-line';
            log.innerHTML = `<span style="color:#444">[${time}]</span> <span class="${e.m.toLowerCase()}">${e.m}</span> <span class="path">${e.p}</span> - 200 OK`;
            
            logsContainer.appendChild(log);
            if (logsContainer.childNodes.length > 8) {
                logsContainer.removeChild(logsContainer.firstChild);
            }
        }
        setInterval(addLog, 2000);

        // 3. Contadores
        let reqs = 14205;
        setInterval(() => {
            reqs += Math.floor(Math.random() * 3);
            document.getElementById('req-count').innerText = reqs.toLocaleString();
        }, 3000);

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