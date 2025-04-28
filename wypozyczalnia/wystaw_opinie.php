<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Przekierowanie na stronę logowania, jeśli użytkownik nie jest zalogowany
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wypozyczalnia";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie danych zalogowanego użytkownika
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM klient WHERE idKlient = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Nie znaleziono użytkownika.";
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Wystaw opinię</title>
    <script>
        function wyslijOpinie() {
            document.getElementById("opinia").value = "";
            alert("Wysłano opinię!");
        }
    </script>
</head>
<body>
    <h1>Wystaw opinię</h1>
    <textarea id="opinia" rows="4" cols="50" placeholder="Wpisz swoją opinię..."></textarea>
    <br>
    <button onclick="wyslijOpinie()">Wyślij</button>
    <br>
    <a href="zarzadzanie_kontem.php">Powrót do zarządzania kontem</a>
</body>
</html>