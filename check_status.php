<?php
$flag_file = __DIR__ . '/maintenance_flag.json';

if (file_exists($flag_file)) {
    $data = json_decode(file_get_contents($flag_file), true);
    if (isset($data['maintenance']) && $data['maintenance'] === true) {
        header('Location: maintenance.php');
        exit;
    }
}
?>
