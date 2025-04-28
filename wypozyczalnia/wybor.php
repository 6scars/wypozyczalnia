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
/////////////////////////////////Dane o kliencie
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "<h2>Witaj, " . htmlspecialchars($user['Imie']) . " " . htmlspecialchars($user['Nazwisko']) . "!</h2>";
    echo "<p>Email: " . htmlspecialchars($user['Email']) . "</p>";
    echo "<p>Telefon: " . htmlspecialchars($user['Telefon']) . "</p>";
} else {
    echo "Nie znaleziono użytkownika.";
    exit();
}
////////////////////////////////Dane o wizycie konkretnego klienta
$czas = "Select last_login from klient WHERE idKlient = $user_id";
$fin = $conn->query($czas);
if ($fin->num_rows > 0) {
    $row = $fin->fetch_assoc();
    echo "Twoja ostatnia wizyta: " . $row['last_login'];
}

//////////////////////////////Dane o ilości odwiedzin na stronie
$licznikPlik = "log.txt";

if(!file_exists($licznikPlik)){
    file_put_contents($licznikPlik,"0");
}

$licznik = (int) file_get_contents($licznikPlik);

echo "<p>Ilość odwiedzin na stronie: ". $licznik. "</p>";

$stmt->close();
$conn->close();


?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wybór samochodów</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .car-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .car-card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
        }
        .car-card img {
            width: 100%;
            height: auto;
        }
        .car-card h3 {
            margin: 10px;
        }
        .car-card p {
            margin: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div>
    <button id="logout-btn">Wyloguj się</button>
        <button id="zarzadzanie-btn"> zarzadzanie_kontem</button>
        <div id="zarzadzanie-kontener"></div>
    </div>
<h1>Wybór samochodów</h1>
<div class="car-container">

    <div class="car-card">
    <a href="opis_samochodu1.html"><img src="photos/car1.jpg" alt="Samochód 1"></a>
        <h3>Samochód 1</h3>
        <p>Opis samochodu 1. To jest piękny samochód, idealny do codziennej jazdy.</p>
    </div>

    <div class="car-card">
        <img src="photos/car2.jpg" alt="Samochód 2">
        <h3>Samochód 2</h3>
        <p>Opis samochodu 2. Wydajny i elegancki, doskonały dla całej rodziny.</p>
    </div>

    <div class="car-card">
        <img src="photos/car3.jpg" alt="Samochód 3">
        <h3>Samochód 3</h3>
        <p>Opis samochodu 3. Sportowy i szybki, idealny dla miłośników adrenaliny.</p>
    </div>

    <div class="car-card">
        <img src="photos/car4.jpg" alt="Samochód 4">
        <h3>Samochód 4</h3>
        <p>Opis samochodu 4. Ekologiczny i nowoczesny, świetny wybór na miasto.</p>
    </div>

</div>
<script>
    document.getElementById("zarzadzanie-btn").addEventListener("click", function() {
        let kontener = document.getElementById("zarzadzanie-kontener");

        if (kontener.innerHTML.trim() !== "") { 
            // Jeśli treść jest już wczytana, ukrywamy ją
            kontener.innerHTML = "";
        } else {
            // Jeśli treść jest pusta, wczytujemy stronę
            fetch("zarzadzanie_kontem.php")
                .then(response => response.text())
                .then(data => {
                    kontener.innerHTML = data;
                })
                .catch(error => console.error("Błąd ładowania strony: ", error));
        }
    });


    document.getElementById("logout-btn").addEventListener("click", function() {
        if (confirm("Czy na pewno chcesz się wylogować?")) {
            window.location.href = "logout.php";
        }
    });
</script>

</body>
</html>
