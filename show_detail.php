<?php 
    $udid    = isset($_GET['UDID']) && $_GET['UDID'] !== '' ? htmlspecialchars($_GET['UDID']) : 'N/A';
    $product = isset($_GET['DEVICE_PRODUCT']) && $_GET['DEVICE_PRODUCT'] !== '' ? htmlspecialchars($_GET['DEVICE_PRODUCT']) : 'N/A';
    $version = isset($_GET['DEVICE_VERSION']) && $_GET['DEVICE_VERSION'] !== '' ? htmlspecialchars($_GET['DEVICE_VERSION']) : 'N/A';
    $name    = isset($_GET['DEVICE_NAME']) && $_GET['DEVICE_NAME'] !== '' ? htmlspecialchars($_GET['DEVICE_NAME']) : 'N/A';

    $subject = "This is my UDID from iOS device";
    $body  = "Hello,\n\nThis is my iOS Device Information:\n";
    $body .= "UDID: {$udid}\n";
    $body .= "Device Product: {$product}\n";
    $body .= "Device Version: {$version}\n";
    $body .= "Device Name: {$name}\n";

    $mailto_subject = rawurlencode($subject);
    $mailto_body    = rawurlencode($body);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TERMINAL // DEVICE LOG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
            box-shadow: 0 0 25px rgba(63, 185, 80, 0.15);
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
            padding: 28px 20px;
            text-align: center;
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
            font-size: 20px;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .sub-text {
            color: #8b949e;
            font-size: 12px;
            margin-bottom: 24px;
        }
        .field-group {
            margin-bottom: 16px;
            text-align: center;
        }
        .field-label {
            color: #8b949e;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .field-box {
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 20px;
            padding: 12px 16px;
            color: #58a6ff;
            font-size: 13px;
            word-break: break-all;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .field-box:active {
            transform: scale(0.98);
            background: #1f242c;
            border-color: #58a6ff;
        }
        .btn {
            display: block;
            width: 100%;
            background: #238636;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 24px;
            box-shadow: 0 4px 12px rgba(35, 134, 54, 0.3);
            transition: all 0.2s ease;
            border: 1px solid #2ea043;
        }
        .btn:active {
            transform: scale(0.97);
            background: #2ea043;
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
            <span class="terminal-title">SYS_LOG // DEVICE_INFO</span>
        </div>

        <div class="terminal-body">
            <div><span class="status-badge">COLLECTED DATA</span></div>
            <h1 class="main-heading">iOS Device Details</h1>
            <p class="sub-text">Tap any field to copy to clipboard</p>

            <div class="field-group">
                <div class="field-label">UDID</div>
                <div class="field-box" onclick="copyText('<?php echo $udid; ?>')">
                    <?php echo $udid; ?>
                </div>
            </div>

            <div class="field-group">
                <div class="field-label">Device Product</div>
                <div class="field-box" onclick="copyText('<?php echo $product; ?>')">
                    <?php echo $product; ?>
                </div>
            </div>

            <div class="field-group">
                <div class="field-label">Device Version</div>
                <div class="field-box" onclick="copyText('<?php echo $version; ?>')">
                    <?php echo $version; ?>
                </div>
            </div>

            <div class="field-group">
                <div class="field-label">Device Name</div>
                <div class="field-box" onclick="copyText('<?php echo $name; ?>')">
                    <?php echo $name; ?>
                </div>
            </div>

            <a class="btn" href="mailto:?subject=<?php echo $mailto_subject ?>&body=<?php echo $mailto_body ?>">
                > SEND VIA EMAIL
            </a>
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
