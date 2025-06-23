<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Medewerker') {
    header('Location: medewerker-login.php');
    exit;
}

// Debug: Toon sessiegegevens als ze bestaan
if (isset($_SESSION['username'])) {
    echo "<div style='background: #dfd; padding: 10px; margin: 10px 0;'>Sessie actief!<br>";
    echo "Gebruikersnaam: " . $_SESSION['username'] . "<br>";
    echo "Rol: " . $_SESSION['role'] . "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Sole Machina | Medewerker Profiel</title>
</head>

<body>
    <header>
        <h1>Medewerker Profiel</h1>
    </header>
    
    <nav>
        <div class="topnav">
            <a href="medewerker-bestellingen.php">Bestellingen</a>
            <a href="medewerker-profiel.php">Profiel</a>
            <a href="logout.php">Uitloggen</a>
        </div>
    </nav>

    <main>
        <section class="profile">
            <h2>Welkom Pietertje</h2>
            <div class="profile-info">
                <button onclick="window.location.href='medewerker-bestellingen.php'">Bekijk en Beheer Bestellingen</button>
                <button id="uitlogKnop" onclick="window.location.href='logout.php'">Uitloggen</button>
            </div>
        </section>
    </main>

    <footer>
        <a href="medewerker-profiel.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="medewerker-profiel.php">Accessibility statement |</a>
        <a href="medewerker-profiel.php">@ 2024 Sole Machina |</a>
        <a href="medewerker-profiel.php">Cookie settings</a>
    </footer>
</body>

</html>