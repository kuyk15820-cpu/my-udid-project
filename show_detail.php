<?php 
    $udid    = isset($_GET['UDID']) ? htmlspecialchars($_GET['UDID']) : 'N/A';
    $product = isset($_GET['DEVICE_PRODUCT']) ? htmlspecialchars($_GET['DEVICE_PRODUCT']) : 'N/A';
    $version = isset($_GET['DEVICE_VERSION']) ? htmlspecialchars($_GET['DEVICE_VERSION']) : 'N/A';
    $name    = isset($_GET['DEVICE_NAME']) ? htmlspecialchars($_GET['DEVICE_NAME']) : 'N/A';
    $serial  = isset($_GET['SERIAL']) && $_GET['SERIAL'] !== '' ? htmlspecialchars($_GET['SERIAL']) : 'N/A (iOS Restricted)';
    $imei    = isset($_GET['IMEI']) && $_GET['IMEI'] !== '' ? htmlspecialchars($_GET['IMEI']) : 'N/A (iOS Restricted)';
    $iccid   = isset($_GET['ICCID']) && $_GET['ICCID'] !== '' ? htmlspecialchars($_GET['ICCID']) : 'N/A (iOS Restricted)';
    $mac     = isset($_GET['MAC_ADDRESS_EN0']) && $_GET['MAC_ADDRESS_EN0'] !== '' ? htmlspecialchars($_GET['MAC_ADDRESS_EN0']) : 'N/A (iOS Restricted)';

    $subject = "Device Information from iOS Device";
    $body  = "Hello,\nThis is my device information:\n";
    $body .= "UDID: {$udid}\n";
    $body .= "Device Name: {$name}\n";
    $body .= "Product: {$product}\n";
    $body .= "Version: {$version}\n";
    $body .= "Serial Number: {$serial}\n";
    $body .= "IMEI: {$imei}\n";

    $mailto_subject = rawurlencode($subject);
    $mailto_body    = rawurlencode($body);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Device Details</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </head>
    <body>
        <div>
            <h1>iOS Device Details</h1>
            <p><strong>UDID:</strong> <?php echo $udid; ?></p>
            <p><strong>Device Name:</strong> <?php echo $name; ?></p>
            <p><strong>Product:</strong> <?php echo $product; ?></p>
            <p><strong>iOS Version:</strong> <?php echo $version; ?></p>
            <p><strong>Serial Number:</strong> <?php echo $serial; ?></p>
            <p><strong>IMEI:</strong> <?php echo $imei; ?></p>
            <p><strong>ICCID (SIM):</strong> <?php echo $iccid; ?></p>
            <p><strong>Wi-Fi MAC Address:</strong> <?php echo $mac; ?></p>

            <hr />
            <p>Send details via email:</p>
            <p>
                <a href="mailto:?subject=<?php echo $mailto_subject; ?>&body=<?php echo $mailto_body; ?>">Send Mail</a>
            </p>
        </div>
    </body>
</html>
