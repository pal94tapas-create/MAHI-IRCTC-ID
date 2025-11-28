<?php
require '../config.php';
if(empty($_SESSION['admin'])){ header('Location: login.php'); exit; }
$conn = db_connect();
$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$orders = $conn->query("SELECT o.*, p.title FROM orders o LEFT JOIN products p ON p.id=o.product_id ORDER BY o.created_at DESC");
$users = $conn->query("SELECT id,name,phone,wallet_balance FROM users ORDER BY created_at DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Dashboard</title></head>
<body>
  <h1>Dashboard</h1>
  <p><a href="add_product.php">Add Product</a> | <a href="settings.php">Settings</a> | <a href="logout.php">Logout</a></p>

  <h2>Users</h2>
  <table border="1">
    <tr><th>ID</th><th>Name</th><th>Phone</th><th>Wallet</th><th>Actions</th></tr>
    <?php while($u = $users->fetch_assoc()): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['phone']) ?></td>
        <td>₹<?= number_format($u['wallet_balance'],2) ?></td>
        <td>
          <form method="POST" action="wallet_add.php" style="display:inline">
            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
            <input name="amount" placeholder="amt">
            <button>Credit</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <h2>Products</h2>
  <table border="1">
    <tr><th>ID</th><th>Title</th><th>Price</th><th>Actions</th></tr>
    <?php while($r = $products->fetch_assoc()): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['title']) ?></td>
        <td>₹<?= number_format($r['price'],2) ?></td>
        <td>
          <a href="edit_product.php?id=<?= $r['id'] ?>">Edit</a> |
          <a href="delete_product.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <h2>Orders</h2>
  <table border="1">
    <tr><th>ID</th><th>Product</th><th>Customer</th><th>UPI ID</th><th>Txn</th><th>Status</th><th>Action</th></tr>
    <?php while($o = $orders->fetch_assoc()): ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['title']) ?></td>
        <td><?= htmlspecialchars($o['customer_name'].' / '.$o['customer_phone']) ?></td>
        <td><?= htmlspecialchars($o['upi_id']) ?></td>
        <td><?= htmlspecialchars($o['upi_txn_id']) ?></td>
        <td><?= $o['status'] ?></td>
        <td>
          <?php if($o['status'] !== 'verified'): ?>
            <a href="verify_order.php?id=<?= $o['id'] ?>">Mark Verified</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

</body></html>