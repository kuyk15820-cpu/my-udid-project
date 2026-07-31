<?php 
    $udid    = isset($_GET['UDID']) && $_GET['UDID'] !== '' ? htmlspecialchars($_GET['UDID']) : 'N/A';
    $imei    = isset($_GET['IMEI']) && $_GET['IMEI'] !== '' ? htmlspecialchars($_GET['IMEI']) : 'N/A';
    $serial  = isset($_GET['SERIAL']) && $_GET['SERIAL'] !== '' ? htmlspecialchars($_GET['SERIAL']) : 'N/A';

    // ดึงค่า Product และ Version มาเชื่อมกันในช่องเดียว
    $product = isset($_GET['DEVICE_PRODUCT']) ? htmlspecialchars($_GET['DEVICE_PRODUCT']) : '';
    $version = isset($_GET['DEVICE_VERSION']) ? htmlspecialchars($_GET['DEVICE_VERSION']) : '';

    // ถ้ามีการส่งค่า PRODUCT_VERSION แบบรวมมาจาก processes_data.php แล้ว ให้ใช้ค่านั้นได้เลย
    if (isset($_GET['PRODUCT_VERSION']) && $_GET['PRODUCT_VERSION'] !== '') {
        $product_version = htmlspecialchars($_GET['PRODUCT_VERSION']);
    } else {
        if ($product !== '' && $version !== '') {
            $product_version = "{$product} / {$version}";
        } elseif ($product !== '') {
            $product_version = $product;
        } else {
            $product_version = 'N/A';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Get UDID info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    
    <!-- ป้องกัน iOS แอบแปลงตัวเลขเป็นเบอร์โทรศัพท์ -->
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent; /* เอาไฮไลท์สีฟ้า/ส้มตอนกดบนมือถือออก */
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
            
            /* รองรับ Viewport Dynamic + ป้องกันการดันหลุดขอบบน */
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
            padding: 18px 16px;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            background: rgba(58, 166, 255, 0.1);
            color: #58a6ff;
            border: 1px solid rgba(88, 166, 255, 0.3);
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 20px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .main-heading {
            color: #f0f6fc;
            font-size: 18px;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .sub-text {
            color: #8b949e;
            font-size: 11px;
            margin-bottom: 16px;
        }
        .field-group {
            margin-bottom: 10px;
            text-align: center;
        }
        .field-label {
            color: #8b949e;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.2px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .field-box {
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 14px;
            padding: 8px 12px;
            color: #58a6ff;
            font-size: 12px;
            word-break: break-all;
            cursor: pointer;
            -webkit-user-select: text;
            user-select: text;
            outline: none; /* เอาขอบน้ำเงินเวลากดเลือกออก */
        }
        .field-box a {
            color: inherit !important;
            text-decoration: none !important;
            pointer-events: none !important;
        }
        /* 🟢 กล่องข้อความ: ลบการย่อขยาย ลบการเปลี่ยนสีออกทั้งหมดเวลากดค้าง */
        .field-box:active, .field-box:focus {
            background: #0d1117;
            border-color: #30363d;
            outline: none;
            transform: none;
        }
        .btn {
            display: block;
            width: 100%;
            background: #238636;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 16px;
            box-shadow: 0 4px 12px rgba(35, 134, 54, 0.3);
            border: 1px solid #2ea043;
            outline: none;
        }
        /* 🟢 ปุ่มกด: ลบการย่อขยาย ลบการเปลี่ยนสีออกทั้งหมดเวลากดค้าง */
        .btn:active, .btn:focus {
            background: #238636;
            outline: none;
            transform: none;
        }
        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #238636;
            color: #fff;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            display: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            white-space: nowrap;
            z-index: 9999;
        }
    </style>
</head>
<body>

    <div class="terminal-card">
        <div class="terminal-header">
            <span class="dot dot-red"></span>
            <span class="dot dot-yellow"></span>
            <span class="dot dot-green"></span>
            <span class="terminal-title">SYS_LOG // DEVICE_INFO</span>
        </div>

        <div class="terminal-body">
            <div><span class="status-badge">COLLECTED DATA</span></div>
            <h1 class="main-heading">iOS Device Details</h1>
            <p class="sub-text">Tap any field to copy to clipboard</p>

            <!-- 1. UDID -->
            <div class="field-group">
                <div class="field-label">UDID</div>
                <div class="field-box" onclick="copyText('<?php echo $udid; ?>', 'UDID')">
                    <?php echo $udid; ?>
                </div>
            </div>

            <!-- 2. IMEI -->
            <div class="field-group">
                <div class="field-label">IMEI</div>
                <div class="field-box" onclick="copyText('<?php echo $imei; ?>', 'IMEI')">
                    <?php echo $imei; ?>
                </div>
            </div>

            <!-- 3. PRODUCT / VERSION -->
            <div class="field-group">
                <div class="field-label">PRODUCT / VERSION</div>
                <div class="field-box" onclick="copyText('<?php echo $product_version; ?>', 'PRODUCT / VERSION')">
                    <?php echo $product_version; ?>
                </div>
            </div>

            <!-- 4. SERIAL -->
            <div class="field-group">
                <div class="field-label">SERIAL</div>
                <div class="field-box" onclick="copyText('<?php echo $serial; ?>', 'SERIAL')">
                    <?php echo $serial; ?>
                </div>
            </div>

            <!-- ปุ่มกลับหน้าแรก index.html -->
            <a class="btn" href="index.html">
                > BACK TO HOME
            </a>
        </div>
    </div>

    <div id="toast" class="toast">Copied to clipboard!</div>

    <script>
        let toastTimeout;
        function copyText(text, label) {
            if (text === 'N/A' || text === '') return;
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('toast');
                toast.innerText = `Copied ${label} to clipboard!`;
                toast.style.display = 'block';
                
                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => {
                    toast.style.display = 'none';
                }, 1800);
            });
        }
    </script>
</body>
</html>
