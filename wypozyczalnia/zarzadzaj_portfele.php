<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Przekierowanie na stronę logowania, jeśli użytkownik nie jest zalogowany
    exit();
}

// Połączenie z bazą danych
$servername = "localhost";
$username = "root"; // Użytkownik bazy danych
$password = ""; // Hasło bazy danych
$dbname = "wypozyczalnia"; // Nazwa bazy danych

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logika dodawania karty i portfela
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $numKarty = $_POST['num_karty'];
    $dataWaznosci = $_POST['data_waznosci'];
    $cvv = $_POST['cvv'];

    // Walidacja numeru karty
    if (!preg_match('/^\d{16}$/', $numKarty)) {
        echo "<script>alert('Numer karty musi mieć 16 cyfr.');</script>";
    } elseif (strtotime($dataWaznosci) < time()) {
        echo "<script>alert('Data ważności musi być w przyszłości.');</script>";
    } elseif (!preg_match('/^\d{3}$/', $cvv)) {
        echo "<script>alert('CVV musi składać się z 3 cyfr.');</script>";
    } else {
        // Dodanie karty do tabeli karta
        $sql = "INSERT INTO karta (NumKarty, DataWaznosci, CVV, Portfel_Klient_idKlient) VALUES (?, ?, ?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $numKarty, $dataWaznosci, $cvv, $user_id);

        if ($stmt->execute()) {
            // Pobranie ID ostatnio dodanej karty
            $idKarta = $stmt->insert_id;
            $stmt->close();

            // Dodanie wpisu do tabeli portfel
            $sql_portfel = "INSERT INTO portfel (idKlient, portfelID) VALUES (?, ?)";
            $stmt_portfel = $conn->prepare($sql_portfel);
            $stmt_portfel->bind_param("ii", $user_id, $idKarta);

            if ($stmt_portfel->execute()) {
                echo "<script>alert('Dodano nową kartę i wpis do portfela!');</script>";
            } else {
                echo "<script>alert('Błąd dodawania do portfela: " . $stmt_portfel->error . "');</script>";
            }

            $stmt_portfel->close();
        } else {
            echo "<script>alert('Błąd dodawania karty: " . $stmt->error . "');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzaj portfelem</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            const numKarty = document.getElementById('num_karty').value;
            const dataWaznosci = document.getElementById('data_waznosci').value;
            const cvv = document.getElementById('cvv').value;

            // Walidacja numeru karty (16 cyfr)
            const cardRegex = /^\d{16}$/;
            if (!cardRegex.test(numKarty)) {
                alert('Numer karty musi mieć 16 cyfr.');
                return false;
            }

            // Walidacja daty ważności
            const today = new Date();
            const expiryDate = new Date(dataWaznosci);
            if (expiryDate < today) {
                alert('Data ważności musi być w przyszłości.');
                return false;
            }

            // Walidacja CVV (3 cyfry)
            const cvvRegex = /^\d{3}$/;
            if (!cvvRegex.test(cvv)) {
                alert('CVV musi składać się z 3 cyfr.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <h1>Zarządzaj portfelem</h1>
    <p>Tutaj będzie można zarządzać swoimi środkami...</p>

    <form method="POST" action="" onsubmit="return validateForm();">
        <label for="num_karty">Numer karty:</label>
        <input type="text" id="num_karty" name="num_karty" required>

        <label for="data_waznosci">Data ważności (YYYY-MM-DD):</label>
        <input type="date" id="data_waznosci" name="data_waznosci" required>

        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" required>

        <button type="submit">Dodaj nową kartę</button>
    </form>

    <br>
    <a href="zarzadzanie_kontem.php">Powrót do zarządzania kontem</a>
</body>
</html>
