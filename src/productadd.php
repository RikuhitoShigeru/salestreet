<?php
session_start();
require 'db.php';

if (!isset($_SESSION['store_id'])) {
    header("Location: login.php");
    exit;
}

$store_id = $_SESSION['store_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? '';
    $discountrate = $_POST["discountrate"] ?? 0;
    $description = $_POST["description"] ?? '';
    $price = $_POST["price"] ?? 0;
    $stock = $_POST["stock"] ?? 0;
    $image_path = $_POST["image_path"] ?? 'images/sample.jpg'; // 今は手入力。あとでアップロード対応にしてもOK

    $stmt = $pdo->prepare("INSERT INTO products (store_id, name, discountrate, description, price, stock, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$store_id, $name, $discountrate, $description, $price, $stock, $image_path]);

    header("Location: shopmypage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>商品登録 - <?= htmlspecialchars($product['name']) ?></title>
  <link rel="stylesheet" href="../assets/css/style.css?v=3">
</head>
<body>
  <h1>商品登録</h1>
  <form method="post" action="">
    <label>商品名：<input type="text" name="name" require></label><br>
    <label>割引率(%)：<input type="number" name="discountrate" require></label><br>
    <label>説明：<textarea name="description" require></textarea></label><br>
    <label>価格（円）：<input type="number" name="price" require></label><br>
    <label>在庫数：<input type="number" name="stock" require></label><br>
    <label>画像パス<input type="text" name="image-path" require></label><br>
    <button type="submit">登録</button>
  </form>
  <p class="center"><a href="shopmypage.php">戻る</a></p>
</body>