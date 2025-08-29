<?php
include('tables_db.php');

$host = "127.0.0.1";
$username = "mary";
$password = "admin123";
$database = "IST2";

try {
    // Connect without DB to create it
    $dbms_connection = new PDO("mysql:host=$host", $username, $password);
    $dbms_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create DB if not exists
    $create_database = "CREATE DATABASE IF NOT EXISTS $database;";
    $dbms_connection->exec($create_database);

    // Now connect to that DB
    $db_connect = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $db_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create tables from array
    foreach ($tables as $table) {
        $db_connect->exec($table);
    }

    echo "Connected & tables created!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
