<?php
require 'config.php';
if(empty($_SESSION['user_id'])){ echo 'Login required'; exit; }
$uid = (int)$_SESSION['user_id'];
$c = db_connect();
$r = $c->query("SELECT wallet_balance FROM users WHERE id=$uid")->fetch_assoc();
$bal = $r['wallet_balance'] ?? 0;
echo 'Wallet balance: ₹'.number_format($bal,2);
?>