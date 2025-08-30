<?php
$host = 'localhost';
$dbname = 'myapp_supermarket';
$user = 'root';
$password = 'Ph125yakyuu';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}
?>