<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? '';
    $password = $_POST["pass"] ?? '';

    if ($id === '' || $password === '') {
        $error = "メールアドレスとパスワードを入力してください。";
    } else {
        // storesテーブルから入力されたidの店舗を取得
        $stmt = $pdo->prepare("SELECT * FROM stores WHERE id = ?");
        $stmt->execute([$id]);
        $store = $stmt->fetch();

        // passwordが正しければマイページに遷移
        if ($store && $store["pass"] === $password) {
            $_SESSION["store_id"] = $store["id"];
            header("Location: shopmypage.php");
            exit;
        } else {
            $error = "メールアドレスまたはパスワードが正しくありません。";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=5">
</head>
<body>
    <header class="site-header">
        <div>
        <h1 class="site-title font-english">SaleStreet</h1>
        <h2 class="important">※店舗管理者用</h2>
        <p>店舗マイページにログインします</p>
        </div>
        <nav>
        <img class="naviikon" src="images/menu.svg" alt="menu">
        </nav>
    </header>
    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <div class="container">
        <form action="login.php" method="POST" class="form">
            <h1 class="font-english center">login</h1>
            <label>ID: <input type="number" name="id" id="id" required></label><br>
            <label>パスワード: <input type="password" name="pass" required></label><br>
            <button type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>