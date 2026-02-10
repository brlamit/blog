<?php
$host = 'dpg-d65j9n8gjchc73f2i1pg-a.oregon-postgres.render.com';
$port = 5432;
$db   = 'blog_3xpv';
$user = 'blog_3xpv_user';
$pass = 'BOs1ROwwIMsawMGj7h9tGqiR7MSi6Xko';
$dsn  = "pgsql:host={$host};port={$port};dbname={$db}";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query("SELECT to_regclass('public.posts') AS exists");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (isset($row['exists']) && $row['exists']) {
        echo "posts table EXISTS: " . $row['exists'] . PHP_EOL;
    } else {
        echo "posts table DOES NOT exist\n";
    }
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
