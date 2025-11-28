<?php
require 'config.php';
header('Content-Type: application/json');
$phone = trim($_POST['phone'] ?? '');
$purpose = trim($_POST['purpose'] ?? 'login'); // e.g., login, register
if(!$phone){ echo json_encode(['ok'=>false,'msg'=>'Phone required']); exit; }
$otp = rand(100000,999999);
$otp_hash = password_hash($otp, PASSWORD_DEFAULT);
$expires = date('Y-m-d H:i:s', time() + 300); // 5 minutes
$c = db_connect();
$stmt = $c->prepare("INSERT INTO otp_requests (phone, otp_hash, purpose, expires_at) VALUES (?,?,?,?)");
$stmt->bind_param('ssss',$phone,$otp_hash,$purpose,$expires);
$stmt->execute();
$show_otp = false;
if(defined('OTP_MODE') && OTP_MODE==='manual'){
    $show_otp = true; // for testing: show OTP to user so they can enter (since no SMS provider configured)
}
// in production, here you'd call SMS API to send $otp
echo json_encode(['ok'=>true,'show_otp'=>$show_otp,'otp'=> $show_otp ? $otp : null, 'msg'=>'OTP generated']);
?>