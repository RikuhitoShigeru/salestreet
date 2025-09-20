<?php
require 'db.php';

// store_id を URL パラメータから取得
$store_id = $_GET['store_id'] ?? null;

if (!$store_id) {
    echo "店舗が指定されていません。";
    exit;
}

// 該当店舗のすべての商品を取得
$stmt = $pdo->prepare("SELECT * FROM products WHERE store_id = ?");
$stmt->execute([$store_id]);
$products = $stmt->fetchAll();

// 店舗名を取得
$storeStmt = $pdo->prepare("SELECT name FROM stores WHERE id = ?");
$storeStmt->execute([$store_id]);
$store = $storeStmt->fetch();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Hebrew:wght@700&family=Yusei+Magic&display=swap" rel="stylesheet">
  <title><?= htmlspecialchars($store['name'] ?? '店舗') ?>-商品一覧</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=5" />
</head>
<body>
  <!-- サイト共通ヘッダー -->
  <header class="site-header">
    <h1 class="site-title font-english">SaleStreet</h1>
    <nav>
      <!-- 店舗管理者用ログインボタン(一般ユーザは使用しない) -->
      <a href="login.php"><button class="admin-login">login</button></a>
      <!-- 検索アイコン(機能未実装) -->
      <img class="naviikon" src="../assets/images/search.svg" alt="search">
      <!-- メニューアイコン(機能未実装) -->
      <img class="naviikon" src="../assets/images/menu.svg" alt="menu">
    </nav>
  </header>
  <!-- 取得した店舗名を表示 -->
  <h2 class="japanese-casual"><?= htmlspecialchars($store['name'] ?? '店舗') ?>の商品一覧</h2>

  <div class="product-list">
    <!-- 取得した商品の数が0の場合 -->
    <?php if (count($products) === 0): ?>
      <p>現在セール中の商品はありません。</p>
    <!-- 取得した商品の数が1以上の場合 -->
    <?php else: ?>
      <!-- 取得した商品をすべて表示 -->
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <div class="product-image-wrapper">
            <?php if (!empty($product['image_path'])): ?>
              <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="商品画像">
            <?php endif; ?>
            <div class="discount-badge">
              <?= htmlspecialchars($product['discountrate']) ?>%OFF
            </div>
          </div>
          <div class="product-info">
            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
            <div class="product-price">
              ¥<?= number_format($product['price']) ?> (<?= number_format($product['discountrate']) ?>%OFF)
            </div>
            <div class="product-meta"><?= htmlspecialchars($product['description']) ?></div>
            <div class="product-meta">在庫：<?= $product['stock'] ?>個</div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>