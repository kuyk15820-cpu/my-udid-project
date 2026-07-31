<?php
session_start();

// 🟢 1. กำหนดรหัสผ่านสำหรับเข้าหน้า Admin ตรงนี้ (เปลี่ยนตามต้องการ)
$ADMIN_PASSWORD = 'MySecretPass1234'; 

// จัดการการ Log out
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in']);
    header('Location: admin.php');
    exit;
}

// จัดการการส่งรหัสผ่านเพื่อ Log in
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $login_error = 'INVALID ACCESS KEY';
    }
}

// ตรวจสอบสถานะการเข้าสู่ระบบ
$is_authenticated = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// จัดการการสลับสถานะ Maintenance (ทำงานเฉพาะเมื่อเข้าสู่ระบบแล้ว)
$flag_file = __DIR__ . '/maintenance_flag.json';
if ($is_authenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maintenance_status'])) {
    $status = $_POST['maintenance_status'] === 'on';
    file_put_contents($flag_file, json_encode(['maintenance' => $status]));
    header('Location: admin.php');
    exit;
}

// อ่านสถานะปัจจุบัน
$is_maintenance = false;
if (file_exists($flag_file)) {
    $data = json_decode(file_get_contents($flag_file), true);
    $is_maintenance = isset($data['maintenance']) ? $data['maintenance'] : false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TERMINAL // ADMIN AUTH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }
        body {
            background-color: #0d1117;
            color: #3fb950;
            font-family: "Courier New", Courier, monospace;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            min-height: 100dvh;
        }
        .terminal-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.5);
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
        .dot { width: 10px; height: 10px; border-radius: 50%; margin-right: 6px; display: inline-block; }
        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }
        .terminal-title { color: #8b949e; font-size: 11px; margin-left: auto; letter-spacing: 1px; }
        .terminal-body { padding: 24px 20px; text-align: center; }
        
        .main-heading { color: #f0f6fc; font-size: 18px; margin-bottom: 6px; font-weight: bold; }
        .sub-text { color: #8b949e; font-size: 11px; margin-bottom: 20px; }
        
        .status-box {
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .status-title { font-size: 11px; color: #8b949e; margin-bottom: 8px; }
        .status-value { font-size: 14px; font-weight: bold; letter-spacing: 1px; }
        .status-online { color: #3fb950; }
        .status-offline { color: #ffbd2e; }

        .input-box {
            width: 100%;
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 8px;
            padding: 12px;
            color: #f0f6fc;
            font-size: 13px;
            font-family: inherit;
            text-align: center;
            margin-bottom: 12px;
            outline: none;
        }
        .input-box:focus { border-color: #58a6ff; }

        .btn {
            display: block;
            width: 100%;
            padding: 14px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            outline: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .btn-auth { background: #1f6beb; color: #ffffff; border: 1px solid #388bfd; }
        .btn-turn-on { background: #238636; color: #ffffff; border: 1px solid #2ea043; }
        .btn-turn-off { background: #bd561d; color: #ffffff; border: 1px solid #d29922; }
        .btn-logout { background: transparent; color: #8b949e; border: 1px solid #30363d; margin-top: 10px; font-weight: normal; }

        .btn:active, .btn:focus { outline: none; transform: none; }
        .error-msg { color: #ff5f56; font-size: 11px; margin-bottom: 12px; }
    </style>
</head>
<body>

    <div class="terminal-card">
        <div class="terminal-header">
            <span class="dot dot-red"></span>
            <span class="dot dot-yellow"></span>
            <span class="dot dot-green"></span>
            <span class="terminal-title">SYS_CTRL // AUTHENTICATION</span>
        </div>

        <div class="terminal-body">
            
            <?php if (!$is_authenticated): ?>
                <!-- 🔒 หน้าฟอร์มใส่รหัสผ่าน -->
                <h1 class="main-heading">Admin Authentication</h1>
                <p class="sub-text">Enter Access Key to manage system</p>

                <?php if ($login_error): ?>
                    <div class="error-msg">[ERROR] <?php echo $login_error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="password" name="password" class="input-box" placeholder="ENTER ACCESS KEY" required autofocus>
                    <button type="submit" class="btn btn-auth">> AUTHENTICATE</button>
                </form>

            <?php else: ?>
                <!-- 🔓 หน้าควบคุมระบบ (แสดงเมื่อใส่รหัสผ่านถูกต้อง) -->
                <h1 class="main-heading">System Control Panel</h1>
                <p class="sub-text">Toggle maintenance mode status</p>

                <div class="status-box">
                    <div class="status-title">CURRENT SYSTEM STATUS</div>
                    <?php if ($is_maintenance): ?>
                        <div class="status-value status-offline">[●] MAINTENANCE MODE (OFFLINE)</div>
                    <?php else: ?>
                        <div class="status-value status-online">[●] SYSTEM ONLINE (NORMAL)</div>
                    <?php endif; ?>
                </div>

                <form method="POST">
                    <?php if ($is_maintenance): ?>
                        <input type="hidden" name="maintenance_status" value="off">
                        <button type="submit" class="btn btn-turn-on">> SWITCH TO ONLINE MODE</button>
                    <?php else: ?>
                        <input type="hidden" name="maintenance_status" value="on">
                        <button type="submit" class="btn btn-turn-off">> SWITCH TO MAINTENANCE MODE</button>
                    <?php endif; ?>
                </form>

                <a href="admin.php?action=logout" class="btn btn-logout" style="text-decoration:none;">LOGOUT</a>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>
