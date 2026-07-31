<?php require_once 'check_status.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Get UDID</title>
        <!-- 🟢 เพิ่ม viewport-fit=cover รองรับจอมือถือที่มีรอยแหว่ง -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        
        <!-- 🟢 ป้องกัน iOS แอบแปลงตัวเลขเป็นลิงก์ -->
        <meta name="format-detection" content="telephone=no">
        <meta name="format-detection" content="date=no">
        <meta name="format-detection" content="address=no">

        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                -webkit-tap-highlight-color: transparent; /* 🟢 ปิดแถบสีไฮไลต์ตอนกดบน Safari/iOS */
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
                
                /* 🟢 ป้องกัน Safari Toolbar ดันหน้าจอ และทำให้จัดให้อยู่ตรงกลางเสมอ */
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
                box-shadow: 0 0 25px rgba(63, 185, 80, 0.15);
                overflow: hidden;
                margin: auto; /* การันตีว่าอยู่ตรงกลางทั้งแนวตั้งและแนวนอน */
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
                padding: 24px 20px; /* กระชับระยะขอบ ให้เห็นปุ่มชัดเจน */
                text-align: center;
            }
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(63, 185, 80, 0.1);
                color: #2ea043;
                border: 1px solid rgba(46, 160, 67, 0.3);
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
                background-color: #3fb950;
                border-radius: 50%;
                box-shadow: 0 0 8px #3fb950;
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
            .accent {
                color: #58a6ff;
            }
            .highlight {
                color: #3fb950;
            }
            .btn {
                display: block;
                width: 100%;
                background: #238636;
                color: #ffffff;
                text-decoration: none;
                padding: 12px 16px;
                border-radius: 20px;
                font-size: 13px;
                font-weight: bold;
                letter-spacing: 1px;
                box-shadow: 0 4px 12px rgba(35, 134, 54, 0.3);
                transition: all 0.2s ease;
                border: 1px solid #2ea043;
                outline: none;
            }
            /* 🟢 ลบการเปลี่ยนสี และการขึ้นกรอบดำเวลากด/โฟกัส */
            .btn:active, .btn:focus {
                background: #238636;
                box-shadow: 0 4px 12px rgba(35, 134, 54, 0.3);
                outline: none;
            }
        </style>
    </head>
    <body>

        <div class="terminal-card">
            <div class="terminal-header">
                <span class="dot dot-red"></span>
                <span class="dot dot-yellow"></span>
                <span class="dot dot-green"></span>
                <span class="terminal-title">SYS_INIT // DEVICE_IDENTIFIER</span>
            </div>

            <div class="terminal-body">
                <div class="status-badge">
                    <span class="pulse-dot"></span> SYSTEM READY
                </div>
                
                <h1 class="main-heading">Get iOS Device UDID</h1>

                <div class="log-box">
                    <div class="log-line"><span class="accent">[STEP 1]</span> Tap button below to request profile</div>
                    <div class="log-line"><span class="accent">[STEP 2]</span> Allow download in Safari browser</div>
                    <div class="log-line"><span class="accent">[STEP 3]</span> Go to Settings -> Install Profile</div>
                    <div class="log-line"><span class="highlight">[INFO]</span> System will collect UDID & Device Log</div>
                </div>

                <a class="btn" href="get_mobileconfig.php">> GET PROFILE</a>
            </div>
        </div>

    </body>
</html>
