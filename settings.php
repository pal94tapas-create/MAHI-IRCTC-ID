<?php
require '../config.php';
if(empty($_SESSION['admin'])){ header('Location: login.php'); exit; }
$c = db_connect();
if($_SERVER['REQUEST_METHOD']==='POST'){
    $server1 = trim($_POST['server1'] ?? '');
    set_setting('server1_mobile', $server1);
    header('Location: settings.php');
    exit;
}
$current = get_setting('server1_mobile');
?>
<form method="POST">
  <label>Server1 Mobile Number</label><br>
  <input name="server1" value="<?= htmlspecialchars($current) ?>"><br>
  <button>Save</button>
</form>
<p>Current: <?= htmlspecialchars($current) ?></p>