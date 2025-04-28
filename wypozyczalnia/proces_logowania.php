<?php
session_start();
require 'db.php'; // Połączenie z bazą danych

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];

    $stmt = $conn->prepare("SELECT idKlient, Haslo FROM Klient WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($haslo, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: wybor.php");
            exit();
        } else {
            echo "Błędne hasło!";
        }
    } else {
        echo "Nie znaleziono użytkownika!";
    }
    $stmt->close();
    $conn->close();
}
?>

