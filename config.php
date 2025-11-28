<?php
// config.php (updated)
session_start();

// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mahi_travel');

// Admin credentials (change immediately)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '1234');

// OTP mode: Manual (system generates OTP; if SMS provider not configured, OTP will be shown for testing)
define('OTP_MODE','manual'); // options: manual, fast2sms, msg91, firebase

function db_connect(){
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($conn->connect_error) die('DB Conn Error: '.$conn->connect_error);
    $conn->set_charset('utf8mb4');
    return $conn;
}

// helper: set/get setting
function set_setting($key,$value){
    $c = db_connect();
    $k = $c->real_escape_string($key);
    $v = $c->real_escape_string($value);
    $c->query("INSERT INTO settings (`key`,`value`) VALUES ('$k','$v') ON DUPLICATE KEY UPDATE `value`='$v'");
}
function get_setting($key){
    $c = db_connect();
    $k = $c->real_escape_string($key);
    $r = $c->query("SELECT `value` FROM settings WHERE `key`='$k' LIMIT 1");
    if($r && $r->num_rows){ $row = $r->fetch_assoc(); return $row['value']; }
    return null;
}
?>