<?php
require 'config.php';
$phone = trim($_POST['phone'] ?? '');
$otp = trim($_POST['otp'] ?? '');
$purpose = trim($_POST['purpose'] ?? 'login');
if(!$phone || !$otp){ echo json_encode(['ok'=>false,'msg'=>'Phone and OTP required']); exit; }
$c = db_connect();
$stmt = $c->prepare("SELECT id, otp_hash, expires_at, used FROM otp_requests WHERE phone=? AND purpose=? ORDER BY id DESC LIMIT 1");
$stmt->bind_param('ss',$phone,$purpose);
$stmt->execute();
$res = $stmt->get_result();
if(!$res || !$res->num_rows){ echo json_encode(['ok'=>false,'msg'=>'No OTP request found']); exit; }
$row = $res->fetch_assoc();
if($row['used']){ echo json_encode(['ok'=>false,'msg'=>'OTP already used']); exit; }
if(strtotime($row['expires_at']) < time()){ echo json_encode(['ok'=>false,'msg'=>'OTP expired']); exit; }
if(password_verify($otp, $row['otp_hash'])){
    // mark used
    $iid = (int)$row['id'];
    $c->query("UPDATE otp_requests SET used=1 WHERE id=$iid");
    echo json_encode(['ok'=>true,'msg'=>'OTP verified']);
} else {
    echo json_encode(['ok'=>false,'msg'=>'Invalid OTP']);
}
?>