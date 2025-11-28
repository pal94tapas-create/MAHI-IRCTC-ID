<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    if(!$phone || !$pass){ echo 'Phone and password required'; exit; }
    $c = db_connect();
    $ph = $c->real_escape_string($phone);
    $r = $c->query("SELECT id FROM users WHERE phone='$ph' LIMIT 1");
    if($r && $r->num_rows){ echo 'User with this phone already exists'; exit; }
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $name_e = $c->real_escape_string($name);
    $c->query("INSERT INTO users (name, phone, password_hash) VALUES ('$name_e','$ph','$hash')");
    echo 'Registered. Please login.';
}
?>