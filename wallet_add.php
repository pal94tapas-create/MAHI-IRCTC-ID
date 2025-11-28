<?php
// admin endpoint to credit user wallet (demo)
require '../config.php';
if(empty($_SESSION['admin'])){ echo 'Admin only'; exit; }
$user_id = (int)($_POST['user_id'] ?? 0);
$amount = (float)($_POST['amount'] ?? 0);
$note = 'Admin credit';
if($user_id<=0 || $amount<=0){ echo 'Invalid'; exit; }
$c = db_connect();
$c->query("UPDATE users SET wallet_balance = wallet_balance + $amount WHERE id=$user_id");
$c->query("INSERT INTO wallet_transactions (user_id, amount, type, note) VALUES ($user_id, $amount, 'credit', '". $c->real_escape_string($note)."')");
echo 'Wallet credited';