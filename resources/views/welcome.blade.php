<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOOD | CYBER CORE V2</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=JetBrains+Mono:wght@300;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #000000;
            --primary: #00ff88;
            --accent: #ff0055;
            --card-bg: rgba(10, 10, 10, 0.9);
            --border: #1a1a1a;
            --font-main: 'JetBrains Mono', monospace;
            --glow: 0 0 15px rgba(0, 255, 136, 0.3);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; cursor: crosshair; }

        body {
            background-color: var(--bg);
            color: #fff;
            font-family: var(--font-main);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* --- BACKGROUND ANIMATION --- */
        #canvas-bg {
            position: absolute;
            top: 0; left: 0;
            z-index: 0;
            opacity: 0.4;
        }

        .grid-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: 
                linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%),
                linear-gradient(90deg, rgba(255, 0, 0, 0.03), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.03));
            background-size: 100% 2px, 3px 100%;
            pointer-events: none;
            z-index: 10;
        }

        /* --- DASHBOARD CONTAINER --- */
        .dashboard {
            position: relative;
            z-index: 2;
            width: 95%;
            max-width: 1000px;
            background: var(--card-bg);
            border: 1px solid var(--primary);
            box-shadow: var(--glow), inset 0 0 20px rgba(0, 255, 136, 0.1);
            padding: 30px;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
            backdrop-filter: blur(5px);
        }

        /* --- LOGO & GLITCH --- */
        .logo-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            border-right: 1px solid var(--border);
            padding-right: 20px;
        }

        .glitch-wrapper {
            position: relative;
        }

        .logo-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 90px;
            font-weight: 900;
            color: var(--primary);
            text-transform: uppercase;
            line-height: 1;
            position: relative;
            text-shadow: 2px 2px var(--accent);
        }

        .logo-title::before {
            content: "MOOD";
            position: absolute;
            left: -2px;
            text-shadow: -2px 0 blue;
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim 5s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim {
            0% { clip: rect(31px, 9999px, 94px, 0); }
            20% { clip: rect(62px, 9999px, 42px, 0); }
            /* ... etc */
            100% { clip: rect(10px, 9999px, 85px, 0); }
        }

        .tagline {
            background: var(--primary);
            color: #000;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 10px;
            margin-top: 10px;
            letter-spacing: 3px;
        }

        /* --- STATS GRID --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            width: 100%;
            margin-top: 40px;
        }

        .stat-card {
            border: 1px solid #333;
            padding: 15px;
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.02);
        }

        .stat-card::after {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 2px; height: 100%;
            background: var(--accent);
        }

        .stat-value {
            font-size: 22px;
            color: #fff;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
        }

        /* --- TERMINAL --- */
        .terminal {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #222;
            height: 350px;
            padding: 15px;
            font-size: 11px;
            display: flex;
            flex-direction: column;
        }

        .term-header {
            border-bottom: 1px solid #222;
            padding-bottom: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            color: var(--accent);
        }

        #logs {
            overflow-y: hidden;
            flex-grow: 1;
        }

        .log-entry {
            margin-bottom: 4px;
            white-space: nowrap;
        }

        .get { color: var(--primary); }
        .post { color: #00e5ff; }
        .warn { color: #ffea00; }

        /* --- SCANLINE EFFECT --- */
        .scanline {
            width: 100%;
            height: 100px;
            z-index: 11;
            background: linear-gradient(0deg, rgba(0, 255, 136, 0) 0%, rgba(0, 255, 136, 0.1) 50%, rgba(0, 255, 136, 0) 100%);
            opacity: 0.1;
            position: absolute;
            bottom: 100%;
            animation: scan 4s linear infinite;
        }

        @keyframes scan {
            to { bottom: -100px; }
        }

    </style>
</head>
<body>

    <div class="grid-overlay"></div>
    <canvas id="canvas-bg"></canvas>

    <div class="dashboard">
        <div class="scanline"></div>
        
        <div class="logo-section">
            <div class="glitch-wrapper">
                <div class="logo-title">MOOD</div>
            </div>
            <div class="tagline">CORE_SYSTEM_ENCRYPTED</div>

            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-label">Data_Inbound</span>
                    <span class="stat-value" id="req-count">14,205</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Latency_ms</span>
                    <span class="stat-value" style="color: var(--primary)">24ms</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Uptime_v0.1</span>
                    <span class="stat-value" id="uptime">00:00:00</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Memory_Usage</span>
                    <span class="stat-value" style="color: var(--accent)">12.4GB</span>
                </div>
            </div>

            <div style="margin-top: 30px; width: 100%;">
                <div style="font-size: 10px; color: #444; margin-bottom: 5px;">CONNECTION_STRENGTH:</div>
                <div style="height: 4px; width: 100%; background: #111;">
                    <div style="height: 100%; width: 85%; background: var(--primary); box-shadow: 0 0 10px var(--primary);"></div>
                </div>
            </div>
        </div>

        <div class="terminal-container">
            <div class="terminal">
                <div class="term-header">
                    <span>> EXECUTION_LOG</span>
                    <span>S_MODE: ACTIVE</span>
                </div>
                <div id="logs"></div>
            </div>
            <p style="color: #444; font-size: 10px; margin-top: 15px; text-align: justify;">
                [INFO] SPAZIO_COSMETIC_SYNC_SUCCESS. BRAIN_NODE: HOSTINGER_PRO_AMER. 
                SECURITY: AES-256. DATABASE: MARIADB_10.6_OPTIMIZED.
            </p>
        </div>
    </div>

    <script>
        // 1. Matrix/Tech Background
        const canvas = document.getElementById('canvas-bg');
        const ctx = canvas.getContext('2d');
        let width, height;

        function init() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', init);
        init();

        const dots = Array(150).fill().map(() => ({
            x: Math.random() * width,
            y: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.5,
            vy: (Math.random() - 0.5) * 0.5
        }));

        function draw() {
            ctx.clearRect(0,0,width,height);
            ctx.fillStyle = '#00ff88';
            dots.forEach(d => {
                d.x += d.vx; d.y += d.vy;
                if(d.x < 0 || d.x > width) d.vx *= -1;
                if(d.y < 0 || d.y > height) d.vy *= -1;
                ctx.fillRect(d.x, d.y, 1.5, 1.5);
            });
            requestAnimationFrame(draw);
        }
        draw();

        // 2. Realistic Log Simulator
        const logs = document.getElementById('logs');
        const commands = [
            {t: 'GET', p: '/prod/inventory/mood_hair', s: '200'},
            {t: 'POST', p: '/auth/handshake', s: '101'},
            {t: 'PATCH', p: '/stock/sync', s: '204'},
            {t: 'GET', p: '/users/session/77x', s: '200'},
            {t: 'WARN', p: '/security/brute_force_block', s: '403'}
        ];

        function addLog() {
            const cmd = commands[Math.floor(Math.random() * commands.length)];
            const time = new Date().toISOString().split('T')[1].split('.')[0];
            const div = document.createElement('div');
            div.className = 'log-entry';
            const colorClass = cmd.t === 'GET' ? 'get' : (cmd.t === 'POST' ? 'post' : 'warn');
            
            div.innerHTML = `[${time}] <span class="${colorClass}">${cmd.t}</span> <span style="color:#888">${cmd.p}</span> <span style="float:right">${cmd.s}</span>`;
            
            logs.appendChild(div);
            if(logs.childNodes.length > 18) logs.removeChild(logs.firstChild);
        }
        setInterval(addLog, 800);

        // 3. Stats Auto-Update
        let reqs = 14205;
        setInterval(() => {
            reqs += Math.floor(Math.random() * 5);
            document.getElementById('req-count').innerText = reqs.toLocaleString();
        }, 2000);

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