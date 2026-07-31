<?php
$flag_file = __DIR__ . '/maintenance_flag.json';

// ตรวจสอบว่าระบบเปลี่ยนกลับเป็น Online หรือยัง
if (file_exists($flag_file)) {
    $data = json_decode(file_get_contents($flag_file), true);
    // ถ้าสถานะเป็น false (Online แล้ว) ให้เด้งกลับหน้า index ทันที
    if (!isset($data['maintenance']) || $data['maintenance'] === false) {
        header('Location: index.php'); // หากใช้ index.html ให้เปลี่ยนเป็น index.html
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TERMINAL // SYSTEM MAINTENANCE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    
    <!-- ป้องกัน iOS แอบแปลงตัวเลขเป็นลิงก์ -->
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }
        html, body {
            min-height: 100%;
            width: 100%;
        }
        body {
            background-color: #0d1117;
            color: #3fb950;
            font-family: "Courier New", Courier, monospace, monospace;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            
            min-height: 100vh;
            min-height: 100dvh;
            overflow-y: auto;
        }
        .terminal-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 0 25px rgba(255, 189, 46, 0.12);
            overflow: hidden;
            margin: auto;
        }
        .terminal-header {
            background: #21262d;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #30363d;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 6px;
            display: inline-block;
        }
        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }
        .terminal-title {
            color: #8b949e;
            font-size: 11px;
            margin-left: auto;
            letter-spacing: 1px;
        }
        .terminal-body {
            padding: 24px 20px;
            text-align: center;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 189, 46, 0.1);
            color: #ffbd2e;
            border: 1px solid rgba(255, 189, 46, 0.3);
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .pulse-dot {
            width: 6px;
            height: 6px;
            background-color: #ffbd2e;
            border-radius: 50%;
            box-shadow: 0 0 8px #ffbd2e;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .main-heading {
            color: #f0f6fc;
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
            line-height: 1.4;
        }
        .sub-text {
            color: #8b949e;
            font-size: 11px;
            margin-bottom: 16px;
            line-height: 1.5;
        }
        .log-box {
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 12px 14px;
            margin: 16px 0 20px 0;
            text-align: left;
            font-size: 11px;
            color: #8b949e;
            line-height: 1.6;
        }
        .log-line {
            margin-bottom: 4px;
        }
        .log-line:last-child {
            margin-bottom: 0;
        }
        .accent-yellow {
            color: #ffbd2e;
        }
        .accent-blue {
            color: #58a6ff;
        }
        .btn-reload {
            display: block;
            width: 100%;
            background: #21262d;
            color: #c9d1d9;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            border: 1px solid #30363d;
            cursor: pointer;
            outline: none;
            font-family: inherit;
        }
        .btn-reload:active, .btn-reload:focus {
            background: #21262d;
            border-color: #30363d;
            outline: none;
            transform: none;
        }
    </style>
</head>
<body>

    <div class="terminal-card">
        <div class="terminal-header">
            <span class="dot dot-red"></span>
            <span class="dot dot-yellow"></span>
            <span class="dot dot-green"></span>
            <span class="terminal-title">SYS_STATUS // OFFLINE</span>
        </div>

        <div class="terminal-body">
            <div class="status-badge">
                <span class="pulse-dot"></span> UNDER MAINTENANCE
            </div>
            
            <h1 class="main-heading">System Maintenance</h1>
            <p class="sub-text">We are performing scheduled updates to improve our services.</p>

            <div class="log-box">
                <div class="log-line"><span class="accent-yellow">[STATUS]</span> System is currently offline</div>
                <div class="log-line"><span class="accent-blue">[ACTION]</span> Upgrading core services & database</div>
                <div class="log-line"><span class="accent-blue">[NOTICE]</span> Please try again in a few minutes</div>
            </div>

            <!-- ปุ่มกดเพื่อรีโหลดหน้าเว็บ (หากระบบ Online แล้ว ปุ่มนี้จะพาไปหน้า index) -->
            <button class="btn-reload" onclick="location.reload()">
                > CHECK SYSTEM STATUS
            </button>
        </div>
    </div>

</body>
</html>
