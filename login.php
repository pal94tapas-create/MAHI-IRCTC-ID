<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $phone = trim($_POST['phone'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    if(!$phone || !$pass){ echo 'Phone and password required'; exit; }
    $c = db_connect();
    $ph = $c->real_escape_string($phone);
    $r = $c->query("SELECT * FROM users WHERE phone='$ph' LIMIT 1");
    if(!$r || !$r->num_rows){ echo 'No user'; exit; }
    $u = $r->fetch_assoc();
    if(password_verify($pass, $u['password_hash'])){
        $_SESSION['user_id'] = $u['id'];
        echo 'Logged in';
    } else { echo 'Invalid credentials'; }
}
?>