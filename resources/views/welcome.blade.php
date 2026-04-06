<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOD | API Control Center V3</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;900&family=JetBrains+Mono:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #030508;
            --panel-bg: rgba(12, 15, 20, 0.85);
            --cyan: #00f3ff;
            --pink: #ff0080;
            --green: #00ff88;
            --border: rgba(255, 255, 255, 0.1);
            --font-main: 'JetBrains Mono', monospace;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: #fff;
            font-family: var(--font-main);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: crosshair;
        }

        /* Efecto de escáner CRT */
        body::after {
            content: " ";
            display: block;
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            z-index: 2;
            background-size: 100% 2px, 3px 100%;
            pointer-events: none;
        }

        #matrix-bg {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            opacity: 0.15;
        }

        .dashboard {
            position: relative;
            z-index: 10;
            width: 98%;
            max-width: 1400px;
            height: 90vh;
            background: radial-gradient(circle at center, rgba(15,20,30,0.9) 0%, rgba(5,8,12,0.95) 100%);
            border: 1px solid #222;
            border-radius: 15px;
            box-shadow: 0 0 50px rgba(0, 243, 255, 0.1), inset 0 0 20px rgba(255, 0, 128, 0.05);
            padding: 30px;
            display: grid;
            grid-template-columns: 1fr 1.5fr 1.2fr;
            gap: 25px;
            backdrop-filter: blur(10px);
        }

        /* --- COLUMNA 1: STATS --- */
        .stats-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-auto-rows: min-content;
            gap: 15px;
        }

        .stat-card {
            background: var(--panel-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--cyan);
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.2);
        }

        .stat-label {
            font-size: 11px;
            color: #8892b0;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
        }

        .color-green { color: var(--green); }
        .color-cyan { color: var(--cyan); }

        /* --- COLUMNA 2: CENTRO MOOD --- */
        .center-col {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .mood-logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 120px;
            font-weight: 900;
            background: linear-gradient(90deg, var(--cyan) 0%, #a200ff 50%, var(--pink) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 20px rgba(0, 243, 255, 0.4)) drop-shadow(0 0 40px rgba(255, 0, 128, 0.4));
            margin-bottom: 10px;
            animation: pulse-glow 3s infinite alternate;
        }

        @keyframes pulse-glow {
            0% { filter: drop-shadow(0 0 15px rgba(0,243,255,0.4)) drop-shadow(0 0 30px rgba(255,0,128,0.4)); }
            100% { filter: drop-shadow(0 0 25px rgba(0,243,255,0.7)) drop-shadow(0 0 50px rgba(255,0,128,0.7)); }
        }

        .subtitle {
            font-size: 22px;
            letter-spacing: 4px;
            color: #ccd6f6;
            margin-bottom: 40px;
        }

        .badges {
            display: flex;
            gap: 15px;
        }

        .badge {
            border: 1px solid var(--green);
            color: var(--green);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 255, 136, 0.05);
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.2);
        }

        .badge-dot {
            width: 8px; height: 8px;
            background: var(--green);
            border-radius: 50%;
            animation: blink 1s infinite;
        }

        @keyframes blink { 50% { opacity: 0; } }

        /* --- COLUMNA 3: TERMINAL & MAPA --- */
        .right-col {
            display: grid;
            grid-template-rows: 1.5fr 1fr;
            gap: 20px;
        }

        .terminal {
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid #333;
            border-radius: 8px;
            padding: 15px;
            font-size: 11px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .term-header {
            color: #666;
            border-bottom: 1px solid #333;
            padding-bottom: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        #logs { flex-grow: 1; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; }
        .log-line { margin-bottom: 4px; white-space: nowrap; font-family: var(--font-main); }
        .time { color: #555; }
        .method.get { color: var(--green); }
        .method.post { color: var(--cyan); }
        .method.put { color: #ffb86c; }
        .path { color: #e2e2e2; }
        .status { color: var(--green); float: right; padding-left: 10px; }

        /* Mapa de Nodos */
        .map-container {
            background: var(--panel-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            background-image: radial-gradient(#333 1px, transparent 1px);
            background-size: 10px 10px;
        }

        .map-title {
            position: absolute; top: 10px; left: 15px;
            font-size: 10px; color: var(--cyan); z-index: 2;
        }

        .node {
            position: absolute;
            width: 6px; height: 6px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        .node-cyan { background: var(--cyan); box-shadow: 0 0 12px var(--cyan); animation: pulse-node 2s infinite; }
        .node-pink { background: var(--pink); box-shadow: 0 0 12px var(--pink); animation: pulse-node 2s infinite reverse; }

        @keyframes pulse-node {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.5); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }

        .footer-text {
            position: absolute; bottom: 20px; width: 100%;
            text-align: center; color: #555; font-size: 11px; z-index: 10;
        }
    </style>
</head>
<body>

    <canvas id="matrix-bg"></canvas>

    <div class="dashboard">
        
        <div class="stats-col">
            <div class="stat-card" style="grid-column: span 2;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 15px;">
                    <span style="font-size: 14px; color: #fff;">API Control Center</span>
                    <span style="font-size: 10px; background: #222; padding: 3px 8px; border-radius: 4px;">Node: GLOBAL_3</span>
                </div>
            </div>

            <div class="stat-card">
                <span class="stat-label">Requests</span>
                <span class="stat-value" id="req-val">14,352</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Uptime</span>
                <span class="stat-value" id="uptime-val">00:00:00</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Status</span>
                <span class="stat-value color-green">200 OK</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Latency</span>
                <span class="stat-value">31ms</span>
            </div>
            <div class="stat-card" style="grid-column: span 2;">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <span class="stat-label">Data Throughput</span>
                        <span class="stat-value" id="thru-val">1.2 GB/s</span>
                    </div>
                    <div style="text-align: right;">
                        <span class="stat-label">Threat Level</span>
                        <span class="stat-value color-green">1/10 <span style="font-size:12px; font-weight:normal;">(Secure)</span></span>
                    </div>
                </div>
                <div style="width: 100%; height: 4px; background: #222; margin-top: 15px; border-radius: 2px; overflow: hidden;">
                    <div style="width: 15%; height: 100%; background: var(--green); box-shadow: 0 0 10px var(--green);"></div>
                </div>
            </div>
        </div>

        <div class="center-col">
            <div class="mood-logo">MOOD</div>
            <div class="subtitle">Professional API Core</div>
            <div class="badges">
                <div class="badge">
                    <div class="badge-dot"></div> MOOD STORE - ONLINE
                </div>
                <div class="badge" style="border-color: var(--cyan); color: var(--cyan); background: rgba(0, 243, 255, 0.05); box-shadow: 0 0 10px rgba(0, 243, 255, 0.2);">
                    <div class="badge-dot" style="background: var(--cyan);"></div> MOBILE APP - CONNECTED
                </div>
            </div>
        </div>

        <div class="right-col">
            <div class="terminal">
                <div class="term-header">
                    <span>LIVE_API_LOGS</span>
                    <span>SSH: HOSTINGER_PRO | v2.4</span>
                </div>
                <div id="logs"></div>
            </div>
            
            <div class="map-container">
                <div class="map-title">GLOBAL_NODE_TRAFFIC // LIVE</div>
                <div class="node node-cyan" style="top: 30%; left: 20%;"></div>
                <div class="node node-cyan" style="top: 45%; left: 25%;"></div>
                <div class="node node-pink" style="top: 35%; left: 45%;"></div>
                <div class="node node-cyan" style="top: 60%; left: 30%;"></div>
                <div class="node node-pink" style="top: 25%; left: 70%;"></div>
                <div class="node node-cyan" style="top: 40%; left: 80%;"></div>
                <div class="node node-cyan" style="top: 70%; left: 85%;"></div>
                
                <svg style="width: 100%; height: 100%; position: absolute; top:0; left:0; z-index: 1;">
                    <path d="M 60 45 Q 120 20 180 50 T 300 35" fill="none" stroke="rgba(0, 243, 255, 0.2)" stroke-width="1" stroke-dasharray="4,4"/>
                    <path d="M 80 80 Q 150 100 250 40 T 350 100" fill="none" stroke="rgba(255, 0, 128, 0.2)" stroke-width="1" stroke-dasharray="4,4"/>
                </svg>
            </div>
        </div>

    </div>

    <div class="footer-text">
        Core de Spazio Cosmetic conectado. Sincronización activa con Flutter App v2.4. Base de datos MariaDB optimizada.
    </div>

    <script>
        // 1. MATRIX BACKGROUND
        const canvas = document.getElementById('matrix-bg');
        const ctx = canvas.getContext('2d');
        
        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        const letters = "0101010101MOODSYSTEMSECURE".split("");
        const fontSize = 14;
        const columns = canvas.width / fontSize;
        const drops = [];
        for(let x = 0; x < columns; x++) drops[x] = 1;

        function drawMatrix() {
            ctx.fillStyle = "rgba(3, 5, 8, 0.05)";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#0055ff"; // Azul oscuro hacker
            ctx.font = fontSize + "px 'JetBrains Mono'";

            for(let i = 0; i < drops.length; i++) {
                const text = letters[Math.floor(Math.random() * letters.length)];
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                if(drops[i] * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0;
                drops[i]++;
            }
        }
        setInterval(drawMatrix, 50);

        // 2. LIVE LOGS SIMULATOR
        const logsContainer = document.getElementById('logs');
        const endpoints = [
            {m: 'GET', p: '/api/v1/user/auth', s: '200 OK'},
            {m: 'POST', p: '/api/v1/cart/item/add', s: '201 Created'},
            {m: 'GET', p: '/api/v1/inventory/mood-hair', s: '200 OK'},
            {m: 'PUT', p: '/api/v1/auth/refresh', s: '200 OK'},
            {m: 'GET', p: '/api/v1/user/profile', s: '200 OK'}
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
                <span class="status ${e.s.includes('201') ? 'color-cyan' : ''}">- ${e.s}</span>
            `;
            
            logsContainer.appendChild(line);
            if(logsContainer.childElementCount > 12) {
                logsContainer.removeChild(logsContainer.firstChild);
            }
        }
        setInterval(addLog, 400);

        // 3. STATS UPDATERS
        let requests = 14352;
        setInterval(() => {
            requests += Math.floor(Math.random() * 8);
            document.getElementById('req-val').innerText = requests.toLocaleString();
        }, 1500);

        setInterval(() => {
            const tb = (Math.random() * (1.8 - 0.8) + 0.8).toFixed(1);
            document.getElementById('thru-val').innerText = `${tb} GB/s`;
        }, 3000);

        let startTime = Date.now() - (82 * 1000); // Empezar en 00:01:22
        setInterval(() => {
            let diff = Date.now() - startTime;
            let h = Math.floor(diff / 3600000).toString().padStart(2, '0');
            let m = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
            let s = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
            document.getElementById('uptime-val').innerText = `${h}:${m}:${s}`;
        }, 1000);
    </script>
</body>
</html>