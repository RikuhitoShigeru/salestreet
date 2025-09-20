<?php
session_start();
require 'db.php';

// 店舗IDがセッションにあるか確認
if (!isset($_SESSION['store_id'])) {
    header("Location: login.php");
    exit;
}

$store_id = $_SESSION['store_id'];

// 店舗情報を取得
$stmt = $pdo->prepare("SELECT * FROM stores WHERE id = ?");
$stmt->execute([$store_id]);
$store = $stmt->fetch();

if (!$store) {
    echo "店舗情報が見つかりません。";
    exit;
}

// 商品一覧を取得
$productStmt = $pdo->prepare("SELECT * FROM products WHERE store_id = ?");
$productStmt->execute([$store_id]);
$products = $productStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND store_id = ?");
    $deleteStmt->execute([$delete_id, $store_id]);

    // 削除後にリロード
    header("Location: shopmypage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Hebrew:wght@700&family=Yusei+Magic&display=swap" rel="stylesheet">
  <title><?= htmlspecialchars($store['name']) ?>-マイページ</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=5" />
</head>
<body>
  <header class="site-header">
    <div>
      <h1 class="site-title font-english">SaleStreet</h1>
      <h2 class="important">※店舗管理者画面</h2>
    </div>
    <nav>
      <img class="naviikon" src="../assets/images/search.svg" alt="search">
      <img class="naviikon" src="../assets/images/menu.svg" alt="menu">
    </nav>
  </header>
  <h1 class="japanese-casual"><?= htmlspecialchars($store['name']) ?></h1>
  <p>所在地：<?= htmlspecialchars($store['address']) ?></p>

  <a href="productadd.php"><button class="admin-login">新しい商品を登録する</button></a>

  <h2>登録済みの商品一覧</h2>

  <div class="product-list">
    <!-- 取得した商品の数が0の場合 -->
    <?php if (count($products) === 0): ?>
      <p>現在登録されている商品はありません。</p>
    <!-- 取得した商品の数が1以上の場合 -->
    <?php else: ?>
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <?php if (!empty($product['image_path'])): ?>
            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="商品画像">
          <?php endif; ?>
          <div class="product-info">
            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
            <div class="product-price">¥<?= number_format($product['price']) ?></div>
            <div class="product-meta"><?= htmlspecialchars($product['description']) ?></div>
            <div class="product-meta">在庫：<?= $product['stock'] ?>個</div>
            <div class="edit-delete">
              <div><a href="productedit.php?id=<?= $product['id'] ?>"><button class="admin-login">編集する</button></a></div>
              <div><form method="post" onsubmit="return confirm('本当に削除しますか？');">
                <input type="hidden" name="delete_id" value="<?= $product['id'] ?>">
                <button type="submit" class="delete-button admin-login">削除する</button>
              </form></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>