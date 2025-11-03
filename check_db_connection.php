<?php

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Remove quotes if present
        if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') || 
            (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
            $value = substr($value, 1, -1);
        }
        
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Get database configuration
$dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbPort = $_ENV['DB_PORT'] ?? '3306';
$dbName = $_ENV['DB_DATABASE'] ?? 'laravel';
$dbUser = $_ENV['DB_USERNAME'] ?? 'root';
$dbPass = $_ENV['DB_PASSWORD'] ?? '';

echo "Memeriksa koneksi database...\n\n";

try {
    // Create DSN
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    
    // Create PDO connection
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ Koneksi database berhasil!\n";
    echo "📍 Host: {$dbHost}\n";
    echo "🔌 Port: {$dbPort}\n";
    echo "🗄️  Database: {$dbName}\n";
    echo "👤 Username: {$dbUser}\n";
    
    // Get MySQL version
    $version = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    echo "🔧 MySQL Version: {$version}\n";
    
    // List tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "\n📋 Tabel yang ada dalam database:\n";
    
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "   - {$table}\n";
        }
    } else {
        echo "   Tidak ada tabel dalam database.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Gagal terhubung ke database!\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    echo "🔍 Konfigurasi database saat ini:\n";
    echo "   - Host: {$dbHost}\n";
    echo "   - Port: {$dbPort}\n";
    echo "   - Database: {$dbName}\n";
    echo "   - Username: {$dbUser}\n";
    echo "   - Password: " . ($dbPass ? '[Tersimpan]' : '[Kosong]') . "\n";
    
    echo "\n💡 Solusi yang mungkin:\n";
    echo "   1. Pastikan server MySQL berjalan pada host dan port yang benar\n";
    echo "   2. Periksa kredensial database (username/password)\n";
    echo "   3. Pastikan database '{$dbName}' sudah ada\n";
    echo "   4. Periksa konfigurasi firewall\n";
}