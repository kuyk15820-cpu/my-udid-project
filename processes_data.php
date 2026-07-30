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

        $xml = xml_parser_create();
        xml_parse_into_struct($xml, $data2, $vs);
        xml_parser_free($xml);

        $UDID = "";
        $DEVICE_PRODUCT = "";
        $DEVICE_VERSION = "";
        $DEVICE_NAME = "";
        $SERIAL = "";
        $IMEI = "";
        $ICCID = "";
        $MAC_ADDRESS_EN0 = "";

        $arrayCleaned = array();
        if (is_array($vs)) {
            foreach ($vs as $v) {
                if (isset($v['level']) && $v['level'] == 3 && isset($v['type']) && $v['type'] == 'complete') {
                    $arrayCleaned[] = $v;
                }
            }
        }

        $iterator = 0;
        foreach ($arrayCleaned as $elem) {
            if (isset($elem['value'])) {
                switch ($elem['value']) {
                    case "UDID":
                        $UDID = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                    case "PRODUCT":
                        $DEVICE_PRODUCT = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                    case "VERSION":
                        $DEVICE_VERSION = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                    case "DEVICE_NAME":
                        $DEVICE_NAME = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                    case "SERIAL":
                        $SERIAL = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                    case "IMEI":
                        $IMEI = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                    case "ICCID":
                        $ICCID = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                    case "MAC_ADDRESS_EN0":
                        $MAC_ADDRESS_EN0 = isset($arrayCleaned[$iterator + 1]['value']) ? $arrayCleaned[$iterator + 1]['value'] : '';
                        break;
                }
            }
            $iterator++;
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
