<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Haal de gebruikersgegevens op uit de database
$db = maakVerbinding();
$sql = "SELECT username, first_name, last_name, address FROM [Users] WHERE username = :gebruikersnaam";
$stmt = $db->prepare($sql);
$stmt->execute(['gebruikersnaam' => $_SESSION['username']]);
$user = $stmt->fetch();

if (!$user) {
    // Gebruiker bestaat niet meer in de database
    echo "<p>Gebruiker niet gevonden.</p>";
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
    <title>Sole Machina | Profiel</title>
</head>

<body>
    <header>
        <h1>Profiel</h1>
        <p>Welkom, <?= htmlspecialchars($user['username']) ?></p>
    </header>
    
    <nav>
        <div class="topnav">
            <a class="active" href="startpagina-ingelogd.php">Startpagina</a>
            <a href="winkelmand.php">Winkelmandje</a>
            <a href="bestellingen.php">Bestellingen</a>
            <a href="profiel.php">Profiel</a>
            <a href="logout.php">Uitloggen</a>
            <a class="split" href="privacy.php">Privacy</a>
        </div>
    </nav>

    <main>
        <section class="profile">
            <h2>Uw Profiel</h2>
            <div class="profile-info">
                <p><strong>Gebruikersnaam:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Voornaam:</strong> <?= htmlspecialchars($user['first_name']) ?></p>
                <p><strong>Achternaam:</strong> <?= htmlspecialchars($user['last_name']) ?></p>
                <p><strong>Adres:</strong> <?= htmlspecialchars($user['address']) ?></p>
                <button onclick="window.location.href='bestellingen.php'">Bestellingen Bekijken</button>
            </div>
        </section>
    </main>

    <footer>
        <a href="startpagina-ingelogd.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="startpagina-ingelogd.php">Accessibility statement |</a>
        <a href="startpagina-ingelogd.php">@ 2024 Sole Machina |</a>
        <a href="startpagina-ingelogd.php">Cookie settings</a>
    </footer>
</body>
</html>