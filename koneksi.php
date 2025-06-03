<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "perpustakaan_digital";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("koneksi gagal: " . $conn->connect_error);
}