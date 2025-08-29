<?php
$user_table = "CREATE TABLE IF NOT EXISTS users (
    User_Id INT AUTO_INCREMENT PRIMARY KEY,
    User_Name VARCHAR(50) NOT NULL,
    User_Email VARCHAR(50) UNIQUE NOT NULL,
    User_Password VARCHAR(255) NOT NULL
);";

$tables = [
    $user_table
];
?>
