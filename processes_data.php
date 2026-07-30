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
        $xml = simplexml_load_string($data2);
        
        $UDID = "";
        $DEVICE_PRODUCT = "";
        $DEVICE_VERSION = "";
        $SERIAL = "";
        $IMEI = "";

        if ($xml !== false) {
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

        // รายชื่อแปลง Model Identifier ให้เป็นชื่อ iPhone
        $modelNames = [
            'iPhone10,3' => 'iPhone X', 'iPhone10,6' => 'iPhone X',
            'iPhone11,2' => 'iPhone XS', 'iPhone11,4' => 'iPhone XS Max', 'iPhone11,6' => 'iPhone XS Max', 'iPhone11,8' => 'iPhone XR',
            'iPhone12,1' => 'iPhone 11', 'iPhone12,3' => 'iPhone 11 Pro', 'iPhone12,5' => 'iPhone 11 Pro Max', 'iPhone12,8' => 'iPhone SE (2nd Gen)',
            'iPhone13,1' => 'iPhone 12 mini', 'iPhone13,2' => 'iPhone 12', 'iPhone13,3' => 'iPhone 12 Pro', 'iPhone13,4' => 'iPhone 12 Pro Max',
            'iPhone14,4' => 'iPhone 13 mini', 'iPhone14,5' => 'iPhone 13', 'iPhone14,2' => 'iPhone 13 Pro', 'iPhone14,3' => 'iPhone 13 Pro Max', 'iPhone14,6' => 'iPhone SE (3rd Gen)',
            'iPhone14,7' => 'iPhone 14', 'iPhone14,8' => 'iPhone 14 Plus', 'iPhone15,2' => 'iPhone 14 Pro', 'iPhone15,3' => 'iPhone 14 Pro Max',
            'iPhone15,4' => 'iPhone 15', 'iPhone15,5' => 'iPhone 15 Plus', 'iPhone16,1' => 'iPhone 15 Pro', 'iPhone16,2' => 'iPhone 15 Pro Max',
            'iPhone17,1' => 'iPhone 16 Pro', 'iPhone17,2' => 'iPhone 16 Pro Max', 'iPhone17,3' => 'iPhone 16', 'iPhone17,4' => 'iPhone 16 Plus'
        ];

        $product_name = isset($modelNames[$DEVICE_PRODUCT]) ? $modelNames[$DEVICE_PRODUCT] : $DEVICE_PRODUCT;
        $product_version = $product_name . " / " . $DEVICE_VERSION;

        $params = "UDID=" . urlencode($UDID) .
                  "&IMEI=" . urlencode($IMEI) .
                  "&PRODUCT_VERSION=" . urlencode($product_version) .
                  "&SERIAL=" . urlencode($SERIAL);

        header("Location: show_detail.php?" . $params, true, 301);
        exit();
    }
}

header("Location: show_detail.php", true, 301);
exit();
