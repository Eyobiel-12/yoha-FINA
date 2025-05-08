<?php
// Simple script to test PostgreSQL connection to Supabase

// First connection attempt - Direct connection
echo "Attempting direct connection...\n";
$host = 'db.qmgcnzweewqlbsgyyutn.supabase.co';
$dbname = 'postgres';
$user = 'postgres';
$password = 'DAhvz2bBksMnex5Z';
$port = 5432;

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Successfully connected to the Supabase PostgreSQL database (direct)!\n";
    $pdo = null;
} catch (PDOException $e) {
    echo "❌ Direct connection failed: " . $e->getMessage() . "\n";
    echo "Trying connection pooler instead...\n\n";
}

// Second connection attempt - Connection pooler
echo "Attempting pooler connection...\n";
$pooler_host = 'aws-0-eu-west-2.pooler.supabase.com';
$pooler_port = 6543;
$pooler_user = 'postgres.qmgcnzweewqlbsgyyutn';
$pooler_password = 'DAhvz2bBksMnex5Z';
$pooler_dbname = 'postgres';

try {
    $dsn = "pgsql:host=$pooler_host;port=$pooler_port;dbname=$pooler_dbname;sslmode=require";
    $pdo = new PDO($dsn, $pooler_user, $pooler_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Successfully connected to the Supabase PostgreSQL database (pooler)!\n";
    
    // Try a simple query
    $stmt = $pdo->query('SELECT current_timestamp');
    $timestamp = $stmt->fetchColumn();
    echo "📅 Current database timestamp: $timestamp\n";
    
    $pdo = null;
} catch (PDOException $e) {
    echo "❌ Pooler connection failed: " . $e->getMessage() . "\n";
}

// Third attempt with connection string
echo "\nAttempting connection string method...\n";
$connection_string = "postgres://postgres.qmgcnzweewqlbsgyyutn:DAhvz2bBksMnex5Z@aws-0-eu-west-2.pooler.supabase.com:6543/postgres?sslmode=require";

try {
    $pdo = new PDO($connection_string);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Successfully connected using connection string!\n";
    
    $pdo = null;
} catch (PDOException $e) {
    echo "❌ Connection string method failed: " . $e->getMessage() . "\n";
}

// Help info
echo "\n";
echo "To configure Laravel for Supabase, update your .env file with:\n";
echo "DB_CONNECTION=pgsql\n";
echo "DB_HOST=aws-0-eu-west-2.pooler.supabase.com\n";
echo "DB_PORT=6543\n";
echo "DB_DATABASE=postgres\n";
echo "DB_USERNAME=postgres.qmgcnzweewqlbsgyyutn\n";
echo "DB_PASSWORD=DAhvz2bBksMnex5Z\n";
echo "\n";
echo "For Vercel deployment, use DATABASE_URL:\n";
echo "DATABASE_URL=postgres://postgres.qmgcnzweewqlbsgyyutn:DAhvz2bBksMnex5Z@aws-0-eu-west-2.pooler.supabase.com:6543/postgres?sslmode=require\n";
echo "\n"; 