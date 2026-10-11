<?php
session_start();

$host = 'db';
$user = 'root';
$password = 'secret'; 
$dbname = 'games_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
?>