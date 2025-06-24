<?php
session_start();
require_once 'db_connection.php';

// Controleer of de gebruiker een medewerker is, anders terug naar login
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Medewerker') {
    header('Location: medewerker-login.php');
    exit;
}

// Haal medewerkergegevens op uit de database
$db = maakVerbinding();
$stmt = $db->prepare("SELECT username, first_name, last_name, address FROM [Users] WHERE username = :username");
$stmt->execute(['username' => $_SESSION['username']]);
$medewerker = $stmt->fetch();

if (!$medewerker) {
    // Medewerker niet gevonden, toon melding en stop
    echo "<p>Medewerker niet gevonden.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">

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
    
    <!-- Navigatiebalk voor medewerkers -->
    <nav>
        <div class="topnav">
            <a href="medewerker-bestellingen.php">Bestellingen</a>
            <a href="medewerker-profiel.php">Profiel</a>
            <a href="logout.php">Uitloggen</a>
        </div>
    </nav>

    <main>
        <section class="profile">
            <h2>Welkom <?= htmlspecialchars($medewerker['first_name'] . ' ' . $medewerker['last_name']) ?></h2>
            <div class="profile-info">
                <p><strong>Gebruikersnaam:</strong> <?= htmlspecialchars($medewerker['username']) ?></p>
                <p><strong>Naam:</strong> <?= htmlspecialchars($medewerker['first_name'] . ' ' . $medewerker['last_name']) ?></p>
                <p><strong>Adres:</strong> <?= htmlspecialchars($medewerker['address']) ?></p>
                <!-- Knop om naar bestellingen te gaan -->
                <button onclick="window.location.href='medewerker-bestellingen.php'">Bekijk en Beheer Bestellingen</button>
                <!-- Uitlogknop -->
                <button id="uitlogKnop" onclick="window.location.href='logout.php'">Uitloggen</button>
            </div>
        </section>
    </main>

    <!-- Footer met links naar medewerker-profiel, behalve privacy -->
    <footer>
        <a href="medewerker-profiel.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="medewerker-profiel.php">Accessibility statement |</a>
        <a href="medewerker-profiel.php">@ 2024 Sole Machina |</a>
        <a href="medewerker-profiel.php">Cookie settings</a>
    </footer>
</body>
</html>