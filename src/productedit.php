<?php
session_start();
require 'db.php';

if (!isset($_SESSION['store_id'])) {
    header("Location: login.php");
    exit;
}

$store_id = $_SESSION['store_id'];
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    echo "error";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND store_id = ?");
$stmt->execute([$product_id, $store_id]);
$product = $stmt->fetch();
if(!$product) {
    echo "error";
    exit;
}

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? '';
    $discountrate = $_POST["discountrate"] ?? 0;
    $description = $_POST["description"] ?? '';
    $price = $_POST["price"] ?? 0;
    $stock = $_POST["stock"] ?? 0;
    $image_path = $_POST["image_path"] ?? 'images/sample.jpg';

    $update = $pdo->prepare("UPDATE products SET name=?, discountrate=?, description=?, price=?, stock=?, image_path=? WHERE id=? AND store_id=?");
    $update->execute([$name, $discountrate, $description, $price, $stock, $image_path, $product_id, $store_id]);
    header("Location: shopmypage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>商品編集 - <?= htmlspecialchars($product['name']) ?></title>
  <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body>
  <h1>商品編集</h1>
  <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=<?= htmlspecialchars($product_id) ?>">
    <label>商品名：<input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>"></label><br>
    <label>割引率(%)：<input type="number" name="discountrate" value="<?= htmlspecialchars($product['discountrate']) ?>"></label><br>
    <label>説明：<textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea></label><br>
    <label>価格（円）：<input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>"></label><br>
    <label>在庫数：<input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>"></label><br>
    <label>画像パス<input type="text" name="image_path" require></label><br>
    <button type="submit">更新</button>
  </form>
  <p class="center"><a href="shopmypage.php">戻る</a></p>
</body>