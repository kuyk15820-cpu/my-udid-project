<?php
// ดึงข้อมูล Payload ที่ iOS ส่งมา
$data = file_get_contents('php://input');

if (!empty($data)) {
    // ตัดเอาเฉพาะส่วนที่เป็น XML
    $plistBegin = '<?xml version="1.0"';
    $plistEnd   = '</plist>';

    $pos1 = strpos($data, $plistBegin);
    $pos2 = strpos($data, $plistEnd);

    if ($pos1 !== false && $pos2 !== false) {
        $data2 = substr($data, $pos1, ($pos2 - $pos1) + 8);
        $xml = simplexml_load_string($data2);
        
        $UDID = "";
        $DEVICE_PRODUCT = "";
        $DEVICE_VERSION = "";
        $SERIAL = "";
        $IMEI = "";

        if ($xml !== false) {
            $nodes = $xml->dict->children();
            $key = null;
            
            // วนลูปอ่านค่า Key ต่างๆ จาก XML
            foreach ($nodes as $node) {
                if ($node->getName() == 'key') {
                    $key = (string)$node;
                } elseif ($node->getName() == 'string' && $key !== null) {
                    switch ($key) {
                        case 'UDID':
                            $UDID = (string)$node;
                            break;
                        case 'PRODUCT':
                            $DEVICE_PRODUCT = (string)$node;
                            break;
                        case 'VERSION':
                            $DEVICE_VERSION = (string)$node;
                            break;
                        case 'SERIAL':
                            $SERIAL = (string)$node;
                            break;
                        case 'IMEI':
                            $IMEI = (string)$node;
                            break;
                    }
                    $key = null;
                }
            }
        }

        // 🟢 ส่ง Parameter แยกกันเพื่อให้ udid-info.php นำไปแปลงชื่อไอโฟนได้
        $params = "UDID=" . urlencode($UDID) .
                  "&IMEI=" . urlencode($IMEI) .
                  "&DEVICE_PRODUCT=" . urlencode($DEVICE_PRODUCT) .
                  "&DEVICE_VERSION=" . urlencode($DEVICE_VERSION) .
                  "&SERIAL=" . urlencode($SERIAL);

        // ⚠️ บังคับใช้ HTTP Status 301 Moved Permanently เท่านั้น เพื่อให้ iOS Profile Service สั่ง Safari เด้งเปิดหน้า udid-info.php
        header("Location: udid-info.php?" . $params, true, 301);
        exit();
    }
}

// ถ้าไม่มีข้อมูล ให้ Redirect กลับหน้าแสดงผลเปล่าๆ
header("Location: udid-info.php", true, 301);
exit();
