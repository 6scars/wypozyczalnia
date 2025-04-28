<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wypozyczalnia";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Pobranie ID zalogowanego użytkownika
$idKlient = $_SESSION['user_id'];

// Zapytanie do pobrania wszystkich kart użytkownika
$sql = "SELECT NumKarty, DataWaznosci FROM karta WHERE Portfel_Klient_idKlient = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idKlient);
$stmt->execute();
$result = $stmt->get_result();

$karty = [];
while ($row = $result->fetch_assoc()) {
    $karty[] = $row;
}

// Zamknięcie połączenia
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Płatności</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .payment-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            text-align: left;
            margin: auto;
            width: 350px;
        }
        .card-box {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #fff;
        }
        .button {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Płatności</h2>

        <?php if (!empty($karty)): ?>
            <p>Twoje zapisane karty:</p>
            <?php foreach ($karty as $karta): ?>
                <div class="card-box">
                    <p><strong>Numer karty:</strong> <?= htmlspecialchars($karta['NumKarty']) ?></p>
                    <p><strong>Data ważności:</strong> <?= htmlspecialchars($karta['DataWaznosci']) ?></p>
                    <button class="button" onclick="alert('Płatność została dokonana kartą <?= htmlspecialchars($karta['NumKarty']) ?>. Dziękujemy!')">Zapłać</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nie masz dodanych żadnych kart płatniczych.</p>
        <?php endif; ?>

        <!-- Przycisk dodawania karty zawsze widoczny -->
        <button class="button" onclick="window.location.href='zarzadzaj_portfele.php'">Dodaj kartę płatniczą</button>
    </div>
</body>
</html>
