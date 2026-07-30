<?php
// get data
$data = file_get_contents('php://input');

if (!empty($data)) {
    // extract XML from data
    $plistBegin = '<?xml version="1.0"';
    $plistEnd   = '</plist>';

    $pos1 = strpos($data, $plistBegin);
    $pos2 = strpos($data, $plistEnd);

    if ($pos1 !== false && $pos2 !== false) {
        $data2 = substr($data, $pos1, ($pos2 - $pos1) + 8);

        // ใช้ SimpleXMLElement พาร์สค่าแทน xml_parse_into_struct เพื่อความแม่นยำของ Unicode / ภาษาไทย
        $xml = simplexml_load_string($data2);
        
        $UDID = "";
        $DEVICE_PRODUCT = "";
        $DEVICE_VERSION = "";
        $DEVICE_NAME = "";
        $SERIAL = "";
        $IMEI = "";
        $ICCID = "";
        $MAC_ADDRESS_EN0 = "";

        if ($xml !== false) {
            // อ่านค่าจาก dict
            $nodes = $xml->dict->children();
            $key = null;
            
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
                        case 'DEVICE_NAME':
                            $DEVICE_NAME = (string)$node;
                            break;
                        case 'SERIAL':
                            $SERIAL = (string)$node;
                            break;
                        case 'IMEI':
                            $IMEI = (string)$node;
                            break;
                        case 'ICCID':
                            $ICCID = (string)$node;
                            break;
                        case 'MAC_ADDRESS_EN0':
                            $MAC_ADDRESS_EN0 = (string)$node;
                            break;
                    }
                    $key = null;
                }
            }
        }

        $params = "UDID=" . urlencode($UDID) .
                  "&DEVICE_PRODUCT=" . urlencode($DEVICE_PRODUCT) .
                  "&DEVICE_VERSION=" . urlencode($DEVICE_VERSION) .
                  "&DEVICE_NAME=" . urlencode($DEVICE_NAME) .
                  "&SERIAL=" . urlencode($SERIAL) .
                  "&IMEI=" . urlencode($IMEI) .
                  "&ICCID=" . urlencode($ICCID) .
                  "&MAC_ADDRESS_EN0=" . urlencode($MAC_ADDRESS_EN0);

        header("Location: show_detail.php?" . $params, true, 301);
        exit();
    }
}

header("Location: show_detail.php", true, 301);
exit();
