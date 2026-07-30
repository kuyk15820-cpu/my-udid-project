<?php 
    $udid            = isset($_GET['UDID']) && $_GET['UDID'] !== '' ? htmlspecialchars($_GET['UDID']) : 'N/A';
    $imei            = isset($_GET['IMEI']) && $_GET['IMEI'] !== '' ? htmlspecialchars($_GET['IMEI']) : 'N/A';
    $product_version = isset($_GET['PRODUCT_VERSION']) && $_GET['PRODUCT_VERSION'] !== '' ? htmlspecialchars($_GET['PRODUCT_VERSION']) : 'N/A';
    $serial          = isset($_GET['SERIAL']) && $_GET['SERIAL'] !== '' ? htmlspecialchars($_GET['SERIAL']) : 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TERMINAL // DEVICE LOG</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #0d1117;
            color: #3fb950;
            font-family: "Courier New", Courier, monospace, monospace;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .terminal-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 0 20px rgba(63, 185, 80, 0.15);
            overflow: hidden;
        }
        .terminal-header {
            background: #21262d;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #30363d;
        }
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
            display: inline-block;
        }
        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }
        .terminal-title {
            color: #8b949e;
            font-size: 12px;
            margin-left: auto;
            letter-spacing: 1px;
        }
        .terminal-body {
            padding: 24px 20px;
        }
        .status-badge {
            display: inline-block;
            background: rgba(58, 166, 255, 0.1);
            color: #58a6ff;
            border: 1px solid rgba(88, 166, 255, 0.3);
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .main-heading {
            color: #f0f6fc;
            font-size: 22px;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .sub-text {
            color: #8b949e;
            font-size: 12px;
            margin-bottom: 24px;
        }
        .field-group {
            margin-bottom: 18px;
            text-align: center;
        }
        .field-label {
            color: #8b949e;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .field-box {
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 25px;
            padding: 14px 16px;
            color: #58a6ff;
            font-size: 14px;
            word-break: break-all;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .field-box:active {
            transform: scale(0.98);
            background: #1f242c;
            border-color: #58a6ff;
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
        }
    </style>
</head>
<body>

    <div class="terminal-card">
        <div class="terminal-header">
            <span class="dot dot-red"></span>
            <span class="dot dot-yellow"></span>
            <span class="dot dot-green"></span>
            <span class="terminal-title">SYS_LOG // UDID_COLLECTED</span>
        </div>

        <div class="terminal-body" style="text-align: center;">
            <div><span class="status-badge">DEVICE INFO</span></div>
            <div class="main-heading">Collected UDID!</div>
            <div class="sub-text">To copy any field, tap on it</div>

            <!-- 1. UDID -->
            <div class="field-group">
                <div class="field-label">UDID</div>
                <div class="field-box" onclick="copyText('<?php echo $udid; ?>')">
                    <?php echo $udid; ?>
                </div>
            </div>

            <!-- 2. IMEI -->
            <div class="field-group">
                <div class="field-label">IMEI</div>
                <div class="field-box" onclick="copyText('<?php echo $imei; ?>')">
                    <?php echo $imei; ?>
                </div>
            </div>

            <!-- 3. PRODUCT / VERSION -->
            <div class="field-group">
                <div class="field-label">PRODUCT / VERSION</div>
                <div class="field-box" onclick="copyText('<?php echo $product_version; ?>')">
                    <?php echo $product_version; ?>
                </div>
            </div>

            <!-- 4. SERIAL -->
            <div class="field-group">
                <div class="field-label">SERIAL</div>
                <div class="field-box" onclick="copyText('<?php echo $serial; ?>')">
                    <?php echo $serial; ?>
                </div>
            </div>

        </div>
    </div>

    <div id="toast" class="toast">Copied to clipboard!</div>

    <script>
        function copyText(text) {
            if (text === 'N/A' || text === '') return;
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('toast');
                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 1500);
            });
        }
    </script>
</body>
</html>
