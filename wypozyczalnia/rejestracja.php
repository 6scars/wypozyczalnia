<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wypozyczalnia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = $conn->real_escape_string($_POST['imie']);
    $nazwisko = $conn->real_escape_string($_POST['nazwisko']);
    $email = $conn->real_escape_string($_POST['email']);
    $telefon = $conn->real_escape_string($_POST['telefon']);
    $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO klient (Imie, Nazwisko, Email, Telefon, haslo) VALUES ('$imie', '$nazwisko', '$email', '$telefon', '$haslo')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Rejestracja zakończona sukcesem!";
    } else {
        echo "Błąd: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Rejestracja</h2>
    <form method="POST" action="">
        <label>Imię:</label>
        <input type="text" name="imie" required><br>
        <label>Nazwisko:</label>
        <input type="text" name="nazwisko" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Telefon:</label>
        <input type="text" name="telefon" required><br>
        <label>Hasło:</label>
        <input type="password" name="haslo" required><br>
        <button type="submit">Zarejestruj się</button>
    </form>
</body>
</html>
