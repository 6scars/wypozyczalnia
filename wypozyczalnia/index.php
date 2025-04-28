<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wypozyczalnia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $haslo = $_POST['haslo'];
    
    $sql = "SELECT * FROM klient WHERE Email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($haslo, $row['haslo'])) {
            $_SESSION['user_id'] = $row['idKlient'];
            $_SESSION['user_name'] = $row['Imie'];
            $sql_update = "UPDATE klient SET last_login = NOW() WHERE idKlient = " . $row['idKlient'];
            $conn->query($sql_update);


            $licznikPlik = "log.txt";

            if(!file_exists($licznikPlik)){
                file_put_contents($licznikPlik,"0");
            }
            
            $licznik = (int) file_get_contents($licznikPlik);
            $licznik ++;
            file_put_contents($licznikPlik, $licznik);

            
            header("Location: wybor.php");
            exit();
        } else {
            echo "Błędne hasło!";
        }
    } else {
        echo "Nie znaleziono użytkownika!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Logowanie</h2>
    <form method="post" action="">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Hasło:</label>
            <input type="password" id="password" name="haslo" required>
        </div>
        <button type="submit">Zaloguj się</button>
        <a href="rejestracja.php">Rejestracja</a>
    </form>
</div>

</body>
</html>