<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wynajem samochodu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .rent-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            text-align: left;
        }
        input {
            display: block;
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-button {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="rent-container">
        <h2>Wynajem samochodu</h2>
        <form action="" method="post">
            <label for="start_date">Termin od:</label>
            <input type="date" id="start_date" name="start_date" required>
            
            <label for="end_date">Termin do:</label>
            <input type="date" id="end_date" name="end_date" required>
            
            <label for="miasto">Miasto:</label>
            <input type="text" id="miasto" name="miasto" required>
            
            <label for="kod_pocztowy">Kod pocztowy:</label>
            <input type="text" id="kod_pocztowy" name="kod_pocztowy" required>
            
            <label for="miejscowosc">Miejscowość:</label>
            <input type="text" id="miejscowosc" name="miejscowosc" required>
            
            <label for="numer_domu">Numer domu:</label>
            <input type="text" id="numer_domu" name="numer_domu" required>
            
            <label for="numer_mieszkania">Numer mieszkania:</label>
            <input type="text" id="numer_mieszkania" name="numer_mieszkania">
            
            <button class="submit-button" type="submit">Zarezerwuj</button>
        </form>
        <br>
        <a href="wybor.php">Powrót do wyboru aut</a>
    </div>

    <?php
    // Kod PHP do obsługi formularza
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Konfiguracja bazy danych
        $servername = "localhost";
        $username = "root"; // Domyślnie użytkownik w XAMPP
        $password = ""; // Puste hasło w XAMPP
        $dbname = "wypozyczalnia";

        // Tworzenie połączenia
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Sprawdzanie połączenia
        if ($conn->connect_error) {
            die("Połączenie nie powiodło się: " . $conn->connect_error);
        }

        // Pobieranie danych z formularza
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $miasto = $_POST['miasto'];
        $kod_pocztowy = $_POST['kod_pocztowy'];
        $miejscowosc = $_POST['miejscowosc'];
        $numer_domu = $_POST['numer_domu'];
        $numer_mieszkania = $_POST['numer_mieszkania'];

        // Przykładowe wartości dla idPojazd i idKlient
        $idPojazd = 1; // Zmień na odpowiedni idPojazd
        $idKlient = $_SESSION['user_id']; // Zmień na odpowiedni idKlient

        // Status i koszt (przykładowe wartości)
        $status = 'Potwierdzona';
        $koszt = 100.00; // Zmień na odpowiednią kwotę

        // Przygotowywanie zapytania SQL
        $sql = "INSERT INTO rezerwacja (idPojazd, idKlient, DataRozpoczecia, DataZakonczenia, Status, Koszt) VALUES (?, ?, ?, ?, ?, ?)";

        // Przygotowywanie i wykonanie zapytania
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssd", $idPojazd, $idKlient, $start_date, $end_date, $status, $koszt);

        if ($stmt->execute()) {
            echo "<p>Rezerwacja została pomyślnie dodana.</p>";
            header("Location: platnosci.php");
            exit();
        } else {
            echo "<p>Błąd: " . $stmt->error . "</p>";
        }

        // Zamknięcie połączenia
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
