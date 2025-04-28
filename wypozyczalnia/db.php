<?php
$host = "localhost";
$user = "root"; // Domyślny użytkownik XAMPP
$password = "";
$database = "wypozyczalnia_aut";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}
?>
