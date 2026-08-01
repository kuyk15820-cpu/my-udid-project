<?php 
    $udid    = isset($_GET['UDID']) && $_GET['UDID'] !== '' ? htmlspecialchars($_GET['UDID']) : 'N/A';
    $imei    = isset($_GET['IMEI']) && $_GET['IMEI'] !== '' ? htmlspecialchars($_GET['IMEI']) : 'N/A';
    $serial  = isset($_GET['SERIAL']) && $_GET['SERIAL'] !== '' ? htmlspecialchars($_GET['SERIAL']) : 'N/A';

    // ดึงค่า Product และ Version มาเชื่อมกันในช่องเดียว
    $product = isset($_GET['DEVICE_PRODUCT']) ? htmlspecialchars($_GET['DEVICE_PRODUCT']) : '';
    $version = isset($_GET['DEVICE_VERSION']) ? htmlspecialchars($_GET['DEVICE_VERSION']) : '';

    // ถ้ามีการส่งค่า PRODUCT_VERSION แบบรวมมาจาก get-udid.php แล้ว ให้ใช้ค่านั้นได้เลย
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

    <!-- 🟢 ดึงไลบรารี GSAP 3 CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent; /* เอาไฮไลท์สีฟ้า/ส้มตอนกดบนมือถือออก */
            
            /* 🟢 ป้องกันการกดค้าง / เมนู Context / ลากคลุมข้อความ (iOS, Android & PC) */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            /* 🟢 ป้องกันการดึง/ลากปุ่ม หรือวัตถุในหน้าเว็บ (Drag & Drop) */
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
        
        /* 🟢 กล่องข้อความ: ป้องกันการคลุมดำ/กดค้าง */
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

            /* 🟢 ป้องกันการคลุมข้อความเฉพาะจุด */
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

        /* 🟢 ครอบเฉพาะ Text Info เพื่อทำ GSAP Entrance Animation ตอนโหลด */
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

        /* 🟢 GSAP Toast Styling */
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

        // 🟢 ดักจับเหตุการณ์กดค้างในระดับ Global/Touch event ป้องกันเมนู context
        document.addEventListener('contextmenu', e => e.preventDefault());

        // 🟢 GSAP Entrance Animation เฉพาะตัวอักษร Text Info ตอนแรก
        document.addEventListener("DOMContentLoaded", () => {
            gsap.to(".info-text", {
                opacity: 1,
                y: 0,
                duration: 0.4,
                stagger: 0.1,
                ease: "power2.out"
            });
        });

        // 🟢 Copy Function + GSAP Toast Popup Animation
        function copyText(text, label) {
            if (text === 'N/A' || text === '') return;

            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('toast');
                toast.innerText = `Copied ${label} to clipboard!`;
                
                // ถ้ารัน Toast อยู่แล้ว ให้กดยกเลิก Tween เก่าเพื่อเริ่มใหม่
                if (toastTween) toastTween.kill();

                // GSAP Toast: เด้งขึ้นมาจากล่าง -> ค้าง 1.5 วินาที -> ยุบจมกลับลงไป
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
