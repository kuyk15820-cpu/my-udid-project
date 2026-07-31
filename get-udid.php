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

        // รวมรหัสโมเดลตรงๆ (เช่น iPhone10,3) เข้ากับ Version (เช่น 20D67)
        $product_version = $DEVICE_PRODUCT;
        if (!empty($DEVICE_VERSION)) {
            $product_version .= " / " . $DEVICE_VERSION;
        }

        // ส่ง Parameter ต่อไปที่หน้า udid-info.php
        $params = "UDID=" . urlencode($UDID) .
                  "&IMEI=" . urlencode($IMEI) .
                  "&PRODUCT_VERSION=" . urlencode($product_version) .
                  "&SERIAL=" . urlencode($SERIAL);

        header("Location: udid-info.php?" . $params, true, 301);
        exit();
    }
}

// ถ้าไม่มีข้อมูล ให้ Redirect กลับหน้าแสดงผลเปล่าๆ
header("Location: udid-info.php", true, 301);
exit();
