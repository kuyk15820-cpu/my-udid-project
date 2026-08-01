<?php 
    $udid    = isset($_GET['UDID']) && $_GET['UDID'] !== '' ? htmlspecialchars($_GET['UDID']) : 'N/A';
    $imei    = isset($_GET['IMEI']) && $_GET['IMEI'] !== '' ? htmlspecialchars($_GET['IMEI']) : 'N/A';
    $serial  = isset($_GET['SERIAL']) && $_GET['SERIAL'] !== '' ? htmlspecialchars($_GET['SERIAL']) : 'N/A';

    // ดึงค่า Product และ Version มาเชื่อมกัน
    $product_raw = isset($_GET['DEVICE_PRODUCT']) ? htmlspecialchars($_GET['DEVICE_PRODUCT']) : '';
    
    // แปลงรหัส Product Identifier เป็นชื่อการตลาดตาม List ล่าสุด
    if (!empty($product_raw)) {
        $apple_models = [
            // ==========================================
            // 📱 iPhone Series
            // ==========================================
            'iPhone1,1' => 'iPhone',
            'iPhone1,2' => 'iPhone 3G',
            'iPhone2,1' => 'iPhone 3GS',
            'iPhone3,1' => 'iPhone 4', 'iPhone3,2' => 'iPhone 4', 'iPhone3,3' => 'iPhone 4',
            'iPhone4,1' => 'iPhone 4S', 'iPhone4,2' => 'iPhone 4S', 'iPhone4,3' => 'iPhone 4S',
            'iPhone5,1' => 'iPhone 5', 'iPhone5,2' => 'iPhone 5',
            'iPhone5,3' => 'iPhone 5C', 'iPhone5,4' => 'iPhone 5C',
            'iPhone6,1' => 'iPhone 5S', 'iPhone6,2' => 'iPhone 5S',
            'iPhone7,2' => 'iPhone 6',
            'iPhone7,1' => 'iPhone 6 Plus',
            'iPhone8,1' => 'iPhone 6S',
            'iPhone8,2' => 'iPhone 6S Plus',
            'iPhone8,4' => 'iPhone SE',
            'iPhone9,1' => 'iPhone 7', 'iPhone9,3' => 'iPhone 7',
            'iPhone9,2' => 'iPhone 7 Plus', 'iPhone9,4' => 'iPhone 7 Plus',
            'iPhone10,1' => 'iPhone 8', 'iPhone10,4' => 'iPhone 8',
            'iPhone10,2' => 'iPhone 8 Plus', 'iPhone10,5' => 'iPhone 8 Plus',
            'iPhone10,3' => 'iPhone X', 'iPhone10,6' => 'iPhone X',
            'iPhone11,2' => 'iPhone XS',
            'iPhone11,4' => 'iPhone XS Max', 'iPhone11,6' => 'iPhone XS Max',
            'iPhone11,8' => 'iPhone XR',
            'iPhone12,1' => 'iPhone 11',
            'iPhone12,3' => 'iPhone 11 Pro',
            'iPhone12,5' => 'iPhone 11 Pro Max',
            'iPhone12,8' => 'iPhone SE 2',
            'iPhone13,1' => 'iPhone 12 mini',
            'iPhone13,2' => 'iPhone 12',
            'iPhone13,3' => 'iPhone 12 Pro',
            'iPhone13,4' => 'iPhone 12 Pro Max',
            'iPhone14,4' => 'iPhone 13 mini',
            'iPhone14,5' => 'iPhone 13',
            'iPhone14,2' => 'iPhone 13 Pro',
            'iPhone14,3' => 'iPhone 13 Pro Max',
            'iPhone14,6' => 'iPhone SE 3',
            'iPhone14,7' => 'iPhone 14',
            'iPhone14,8' => 'iPhone 14 Plus',
            'iPhone15,2' => 'iPhone 14 Pro',
            'iPhone15,3' => 'iPhone 14 Pro Max',
            'iPhone15,4' => 'iPhone 15',
            'iPhone15,5' => 'iPhone 15 Plus',
            'iPhone16,1' => 'iPhone 15 Pro',
            'iPhone16,2' => 'iPhone 15 Pro Max',
            'iPhone17,3' => 'iPhone 16',
            'iPhone17,4' => 'iPhone 16 Plus',
            'iPhone17,1' => 'iPhone 16 Pro',
            'iPhone17,2' => 'iPhone 16 Pro Max',
            'iPhone17,5' => 'iPhone 16e',
            'iPhone18,1' => 'iPhone 17 Pro',
            'iPhone18,2' => 'iPhone 17 Pro Max',
            'iPhone18,3' => 'iPhone 17',
            'iPhone18,4' => 'iPhone Air',

            // ==========================================
            // 📖 iPad Base Models
            // ==========================================
            'iPad1,1' => 'iPad',
            'iPad2,1' => 'iPad 2', 'iPad2,2' => 'iPad 2', 'iPad2,3' => 'iPad 2', 'iPad2,4' => 'iPad 2',
            'iPad3,1' => 'iPad 3', 'iPad3,2' => 'iPad 3', 'iPad3,3' => 'iPad 3',
            'iPad3,4' => 'iPad 4', 'iPad3,5' => 'iPad 4', 'iPad3,6' => 'iPad 4',
            'iPad6,11' => 'iPad 5', 'iPad6,12' => 'iPad 5',
            'iPad7,5' => 'iPad 6', 'iPad7,6' => 'iPad 6',
            'iPad7,11' => 'iPad 7', 'iPad7,12' => 'iPad 7',
            'iPad11,6' => 'iPad 8', 'iPad11,7' => 'iPad 8',
            'iPad12,1' => 'iPad 9', 'iPad12,2' => 'iPad 9',
            'iPad13,18' => 'iPad 10', 'iPad13,19' => 'iPad 10',
            'iPad15,7' => 'iPad (A16)', 'iPad15,8' => 'iPad (A16)',

            // ==========================================
            // 🪶 iPad Air Series
            // ==========================================
            'iPad4,1' => 'iPad Air', 'iPad4,2' => 'iPad Air', 'iPad4,3' => 'iPad Air',
            'iPad5,3' => 'iPad Air 2', 'iPad5,4' => 'iPad Air 2',
            'iPad11,3' => 'iPad Air 3', 'iPad11,4' => 'iPad Air 3',
            'iPad13,1' => 'iPad Air 4', 'iPad13,2' => 'iPad Air 4',
            'iPad13,16' => 'iPad Air 5', 'iPad13,17' => 'iPad Air 5',
            'iPad14,8' => 'iPad Air 11-inch (M2)', 'iPad14,9' => 'iPad Air 11-inch (M2)',
            'iPad15,3' => 'iPad Air 11-inch (M3)', 'iPad15,4' => 'iPad Air 11-inch (M3)',
            'iPad14,10' => 'iPad Air 13-inch (M2)', 'iPad14,11' => 'iPad Air 13-inch (M2)',
            'iPad15,5' => 'iPad Air 13-inch (M3)', 'iPad15,6' => 'iPad Air 13-inch (M3)',

            // ==========================================
            // ✏️ iPad Mini Series
            // ==========================================
            'iPad2,5' => 'iPad Mini', 'iPad2,6' => 'iPad Mini', 'iPad2,7' => 'iPad Mini',
            'iPad4,4' => 'iPad Mini 2', 'iPad4,5' => 'iPad Mini 2', 'iPad4,6' => 'iPad Mini 2',
            'iPad4,7' => 'iPad Mini 3', 'iPad4,8' => 'iPad Mini 3', 'iPad4,9' => 'iPad Mini 3',
            'iPad5,1' => 'iPad Mini 4', 'iPad5,2' => 'iPad Mini 4',
            'iPad11,1' => 'iPad Mini 5', 'iPad11,2' => 'iPad Mini 5',
            'iPad14,1' => 'iPad Mini 6', 'iPad14,2' => 'iPad Mini 6',
            'iPad16,1' => 'iPad Mini (A17 Pro)', 'iPad16,2' => 'iPad Mini (A17 Pro)',

            // ==========================================
            // 🚀 iPad Pro Series
            // ==========================================
            'iPad6,3' => 'iPad Pro 9.7-inch', 'iPad6,4' => 'iPad Pro 9.7-inch',
            'iPad7,3' => 'iPad Pro 10.5-inch', 'iPad7,4' => 'iPad Pro 10.5-inch',
            'iPad8,1' => 'iPad Pro 11-inch', 'iPad8,2' => 'iPad Pro 11-inch', 'iPad8,3' => 'iPad Pro 11-inch', 'iPad8,4' => 'iPad Pro 11-inch',
            'iPad8,9' => 'iPad Pro 11-inch 2', 'iPad8,10' => 'iPad Pro 11-inch 2',
            'iPad13,4' => 'iPad Pro 11-inch 3', 'iPad13,5' => 'iPad Pro 11-inch 3', 'iPad13,6' => 'iPad Pro 11-inch 3', 'iPad13,7' => 'iPad Pro 11-inch 3',
            'iPad14,3' => 'iPad Pro 11-inch (M2)', 'iPad14,4' => 'iPad Pro 11-inch (M2)',
            'iPad16,3' => 'iPad Pro 11-inch (M4)', 'iPad16,4' => 'iPad Pro 11-inch (M4)',
            'iPad17,1' => 'iPad Pro 11-inch (M5)', 'iPad17,2' => 'iPad Pro 11-inch (M5)',
            'iPad6,7' => 'iPad Pro 12.9-inch', 'iPad6,8' => 'iPad Pro 12.9-inch',
            'iPad7,1' => 'iPad Pro 12.9-inch 2', 'iPad7,2' => 'iPad Pro 12.9-inch 2',
            'iPad8,5' => 'iPad Pro 12.9-inch 3', 'iPad8,6' => 'iPad Pro 12.9-inch 3', 'iPad8,7' => 'iPad Pro 12.9-inch 3', 'iPad8,8' => 'iPad Pro 12.9-inch 3',
            'iPad8,11' => 'iPad Pro 12.9-inch 4', 'iPad8,12' => 'iPad Pro 12.9-inch 4',
            'iPad13,8' => 'iPad Pro 12.9-inch 5', 'iPad13,9' => 'iPad Pro 12.9-inch 5', 'iPad13,10' => 'iPad Pro 12.9-inch 5', 'iPad13,11' => 'iPad Pro 12.9-inch 5',
            'iPad14,5' => 'iPad Pro 12.9-inch (M2)', 'iPad14,6' => 'iPad Pro 12.9-inch (M2)',
            'iPad16,5' => 'iPad Pro 13-inch (M4)', 'iPad16,6' => 'iPad Pro 13-inch (M4)',
            'iPad17,3' => 'iPad Pro 13-inch (M5)', 'iPad17,4' => 'iPad Pro 13-inch (M5)'
        ];

        $product = isset($apple_models[$product_raw]) ? $apple_models[$product_raw] : $product_raw;
    } else {
        $product = $product_raw;
    }

    $version = isset($_GET['DEVICE_VERSION']) ? htmlspecialchars($_GET['DEVICE_VERSION']) : '';

    // การจัดการ PRODUCT_VERSION
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
    
    <!-- ป้องกัน iOS แปลงตัวเลขเป็นลิงก์อัตโนมัติ -->
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">

    <!-- 🟢 GSAP 3 CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
            
            /* 🟢 ป้องกันการกดค้าง / Context Menu / Drag Text */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
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
            outline: none;

            -webkit-touch-callout: none !important;
            -webkit-user-select: none !important;
            -khtml-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }
        .field-box a {
            color: inherit !important;
            text-decoration: none !important;
            pointer-events: none !important;
        }

        .info-text {
            display: inline-block;
            opacity: 0;
            transform: translateY(8px);
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
            cursor: pointer;
            font-family: inherit;
        }
        .btn:active, .btn:focus {
            background: #238636;
            outline: none;
            transform: none;
        }

        .toast {
            position: fixed;
            bottom: 25px;
            left: 50%;
            background: #238636;
            color: #fff;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
            white-space: nowrap;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transform: translate(-50%, 20px);
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
                <div class="field-box" 
                     oncontextmenu="return false;" 
                     ondragstart="return false;" 
                     onclick="copyText('<?php echo $udid; ?>', 'UDID')">
                    <span class="info-text"><?php echo $udid; ?></span>
                </div>
            </div>

            <!-- 2. IMEI -->
            <div class="field-group">
                <div class="field-label">IMEI</div>
                <div class="field-box" 
                     oncontextmenu="return false;" 
                     ondragstart="return false;" 
                     onclick="copyText('<?php echo $imei; ?>', 'IMEI')">
                    <span class="info-text"><?php echo $imei; ?></span>
                </div>
            </div>

            <!-- 3. PRODUCT / VERSION -->
            <div class="field-group">
                <div class="field-label">PRODUCT / VERSION</div>
                <div class="field-box" 
                     oncontextmenu="return false;" 
                     ondragstart="return false;" 
                     onclick="copyText('<?php echo $product_version; ?>', 'PRODUCT / VERSION')">
                    <span class="info-text"><?php echo $product_version; ?></span>
                </div>
            </div>

            <!-- 4. SERIAL -->
            <div class="field-group">
                <div class="field-label">SERIAL</div>
                <div class="field-box" 
                     oncontextmenu="return false;" 
                     ondragstart="return false;" 
                     onclick="copyText('<?php echo $serial; ?>', 'SERIAL')">
                    <span class="info-text"><?php echo $serial; ?></span>
                </div>
            </div>

            <!-- ปุ่มกลับหน้าแรก index.html -->
            <button type="button" 
                    class="btn" 
                    oncontextmenu="return false;" 
                    ondragstart="return false;" 
                    onclick="window.location.href='index.html'">
                > BACK TO HOME
            </button>
        </div>
    </div>

    <div id="toast" class="toast">Copied to clipboard!</div>

    <script>
        let toastTween;

        document.addEventListener('contextmenu', e => e.preventDefault());

        document.addEventListener("DOMContentLoaded", () => {
            gsap.to(".info-text", {
                opacity: 1,
                y: 0,
                duration: 0.4,
                stagger: 0.1,
                ease: "power2.out"
            });
        });

        function copyText(text, label) {
            if (text === 'N/A' || text === '') return;

            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('toast');
                toast.innerText = `Copied ${label} to clipboard!`;
                
                if (toastTween) toastTween.kill();

                toastTween = gsap.timeline()
                    .to(toast, {
                        opacity: 1,
                        y: 0,
                        duration: 0.3,
                        ease: "back.out(1.4)"
                    })
                    .to(toast, {
                        opacity: 0,
                        y: 20,
                        duration: 0.3,
                        delay: 1.5,
                        ease: "power2.in"
                    });
            });
        }
    </script>
</body>
</html>
