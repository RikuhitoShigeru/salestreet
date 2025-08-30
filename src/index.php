<?php
require 'db.php';

// 店舗一覧を取得（storesテーブル）
$stmt = $pdo->query("SELECT * FROM stores");
$stores = $stmt->fetchAll();

$product_counts = [];
foreach ($stores as $store) {
  $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM products WHERE store_id = ?");
  $stmt2->execute([$store['id']]);
  $product_counts[$store['id']] = $stmt2->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Hebrew:wght@700&family=Yusei+Magic&display=swap" rel="stylesheet">
  <title>SaleStreet - トップページ</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=5"/>
</head>
<body>
  <header class="site-header">
    <h1 class="site-title font-english">SaleStreet</h1>
    <nav>
      <a href="login.php"><button class="admin-login">login</button></a>
      <img class="naviikon" src="../assets/images/search.svg" alt="search">
      <img class="naviikon" src="../assets/images/menu.svg" alt="menu">
    </nav>
  </header>

  <main>
    <h2 class="japanese-casual">店舗一覧</h2>
    <div class="shop-list">
      <?php foreach ($stores as $store): ?>
        <a href="productlist.php?store_id=<?= htmlspecialchars($store['id']) ?>" class="shop-card-link">
          <div class="shop-card">
            <div class="shop-name"><?= htmlspecialchars($store['name']) ?></div>
            <div class="shop-address"><?= htmlspecialchars($store['address']) ?></div>
            <div class="shop-info">セール中商品：<?= $product_counts[$store['id']] ?>件</div> <!-- 商品数は後で対応 -->
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>