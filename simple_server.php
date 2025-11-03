<?php
echo "<h1>Laravel CMS Kampus</h1>";
echo "<p>Server is running on port 8000</p>";
echo "<p>Database connection: ";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=8889;dbname=cms_multi_bahasa', 'root', 'root');
    echo "✅ Connected</p>";
    echo "<p>Database tables: ";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo implode(", ", $tables) . "</p>";
} catch (PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "</p>";
}
?>